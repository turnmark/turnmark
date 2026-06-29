#!/usr/bin/env bash

set -euo pipefail

version="$1"

vendor/bin/monorepo-builder release "$version"

git push origin main
git push origin "$version"

for dir in packages/*; do
    package=$(basename "$dir")

    echo "==> ${package}"

    git subtree split \
        --prefix="$dir" \
        --branch="split-$package"

    git push \
        "git@github.com:turnmark/$package.git" \
        "split-$package:main" \
        --force

    git push \
        "git@github.com:turnmark/$package.git" \
        "${version}"

    gh release create \
        "$version" \
        --repo "turnmark/$package" \
        --generate-notes \
        || true

    git branch -D "split-$package"
done
