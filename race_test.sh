#!/bin/bash

# race_test.sh - Test the race condition protection in the "Take" action
# 
# This script simulates two concurrent requests to accept the same repair request.
# One should succeed, the second should be rejected with a clear error.
#
# Usage:
#   ./race_test.sh <REQUEST_ID> <SESSION_COOKIE>
#
# Example:
#   SESSION=$(curl -s -c /tmp/cookies.txt -X POST http://localhost:8000/login \
#     -H "Content-Type: application/x-www-form-urlencoded" \
#     -d "email=master1@example.com&password=password&_token=_token_here" \
#     | grep -o 'Set-Cookie: [^;]*' | head -1 | cut -d' ' -f3)
#   ./race_test.sh 1 "$SESSION"

set -e

REQUEST_ID="${1:-1}"
SESSION_COOKIE="${2:-}"
BASE_URL="${BASE_URL:-http://localhost}"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${YELLOW}=== Race Condition Test ===${NC}"
echo -e "Base URL: $BASE_URL"
echo -e "Request ID: $REQUEST_ID"
echo ""

if [ -z "$SESSION_COOKIE" ]; then
    echo -e "${YELLOW}No session cookie provided. Getting one...${NC}"
    
    # Try to login and get session
    SESSION_COOKIE=$(curl -s -c /tmp/race_test_cookies.txt -X POST "$BASE_URL/login" \
        -H "Content-Type: application/x-www-form-urlencoded" \
        -d "email=master1@example.com&password=password&_token=test" \
        -L 2>&1 | grep -o 'LARAVEL_SESSION=[^;]*' | head -1 || echo "")
    
    if [ -z "$SESSION_COOKIE" ]; then
        echo -e "${RED}Failed to obtain session cookie. Make sure the app is running and master1@example.com exists.${NC}"
        exit 1
    fi
fi

echo -e "${GREEN}Session cookie: ${SESSION_COOKIE:0:30}...${NC}"
echo ""

# Function to make the take request
make_request() {
    local request_num=$1
    local output_file="/tmp/race_test_response_$request_num.txt"
    
    echo -e "${YELLOW}[Request $request_num] Sending take request...${NC}"
    
    curl -s -w "\nHTTP_CODE:%{http_code}\n" \
        -b "$SESSION_COOKIE" \
        -X POST "$BASE_URL/master/requests/$REQUEST_ID/take" \
        -L > "$output_file" 2>&1
    
    local http_code=$(grep "HTTP_CODE:" "$output_file" | cut -d':' -f2 | tr -d ' ')
    
    if [ "$http_code" = "200" ]; then
        echo -e "${GREEN}[Request $request_num] Status: $http_code (Success - page loaded)${NC}"
        cat "$output_file" | grep -o "error\|success" | head -1 || echo ""
    elif [ "$http_code" = "302" ]; then
        echo -e "${GREEN}[Request $request_num] Status: $http_code (Redirect - likely success)${NC}"
    elif [ "$http_code" = "409" ]; then
        echo -e "${RED}[Request $request_num] Status: $http_code (Conflict - already taken)${NC}"
    else
        echo -e "${YELLOW}[Request $request_num] Status: $http_code${NC}"
    fi
    
    # Check for error message in response
    if grep -q "already being worked on\|error" "$output_file"; then
        echo -e "${RED}[Request $request_num] Found error message in response${NC}"
    fi
    
    echo ""
}

# Run two requests in parallel
echo -e "${YELLOW}Sending two concurrent take requests...${NC}"
make_request 1 &
PID1=$!

sleep 0.5  # Small delay to increase chance of race condition

make_request 2 &
PID2=$!

# Wait for both to complete
wait $PID1
wait $PID2

echo -e "${YELLOW}=== Test Complete ===${NC}"
echo ""
echo -e "Expected results:"
echo -e "  ${GREEN}[Request 1] HTTP 200/302: Successfully took the request${NC}"
echo -e "  ${RED}[Request 2] HTTP 302/409: Request already taken (or error flash)${NC}"
echo ""
echo "Note: Due to network timing, both requests might succeed if execution is not truly parallel."
echo "To improve parallelism, run this script with a shorter delay:"
echo "  sed -i 's/sleep 0.5/sleep 0.1/' race_test.sh"
echo ""
echo "Cleanup:"
rm -f /tmp/race_test_response_*.txt
echo "Response files cleaned up."
