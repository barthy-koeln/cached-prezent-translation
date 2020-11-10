#!/usr/bin/env bash

# Use the error status of the first failure, rather than that of the last item in a pipeline.
set -o pipefail

PERCENT=$(./vendor/bin/phpunit ./tests --coverage-text --colors=never | tee /dev/tty | grep -Eo --color=never '^\s*Lines:\s*([[:digit:]]+)' | grep -Eo --color=never '([[:digit:]]+)')

if [ -z "$PERCENT" ]; then
  echo -e "Percentage check failed."
  exit 1
fi

if [[ ${PERCENT} -gt 90 ]]; then
  COLOR=green
elif [[ ${PERCENT} -gt 75 ]]; then
  COLOR=yellow
elif [[ ${PERCENT} -gt 50 ]]; then
  COLOR=orange
else
  COLOR=red
fi

echo -e "\nUpdating Coverage Badge:"
echo -e "Percentage: ${PERCENT}"
echo -e "Color: ${COLOR}"

if [ -z "$BADGE_TOKEN" ]; then
  echo -e "No badge token set."
  exit 0
fi

curl https://badges.barthy.koeln/badge/cached-prezent-translation/coverage \
  --header "Content-Type: application/json" \
  --header "X-Auth-Token: $BADGE_TOKEN" \
  --request POST \
  --data @<(
    cat <<EOF
        {
            "schemaVersion": 1,
            "label": "coverage",
            "message": "$PERCENT %",
            "color": "${COLOR}"
        }
EOF
  )

echo -e "\n\n"

exit 0
