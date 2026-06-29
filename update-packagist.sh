#!/bin/sh

set -eu

PACKAGIST_TOKEN=""

PACKAGE_NAMES="
scraper
scraper-fukuoka
scraper-mikuni
scraper-tamagawa
scraper-tokuyama
"

for PACKAGE_NAME in $PACKAGE_NAMES; do
  echo "Triggering Packagist update: ${PACKAGE_NAME}"

  curl --fail --silent --show-error \
    -X POST "https://packagist.org/api/update-package" \
    -H "Content-Type: application/json" \
    -H "Authorization: Bearer shimomo:${PACKAGIST_TOKEN}" \
    -d "{\"repository\":\"https://github.com/turnmark/${PACKAGE_NAME}\"}"

  echo "✓ ${PACKAGE_NAME}"
done

echo
echo "All updates have been triggered."
