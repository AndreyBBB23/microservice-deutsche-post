#!/bin/bash
# Script for pulling from master.
# fetches, checks status, prompts to continue, runs composer install.

# Terminate upon error
set -eu

# Do not allow run as root
if [[ $EUID == 0 ]]; then
  echo "Do not run stuff as root!";
  exit 1;
fi

# Fetch git information
git fetch -v

# Show git status to double-check that directory is clean (no conflicts plz!)
git status -uno
echo "";

# Test if there's actually anything to do
if ! git diff --quiet remotes/origin/HEAD; then
  echo "There are changes!";
else
  echo "No changes, exiting script.";
  exit;
fi


# Prompt to continue
echo "";
read -p "Does everything look good? Press Enter to continue with git pull."
echo "Git pulling now!";

# git pull from master
git pull

# Run composer install, just to be sure.
#   Possible TODO: check if composer.json/.lock changed in gitlog.
composer install --no-dev -o

# Compare .env and .env.example to see if any keys have changed
if ! diff <(cut -d'=' -f1 .env.example|sort|uniq) <(cut -d'=' -f1 .env|sort|uniq); then
  echo -e "\n\033[0;31mWARNING: .env.example has changes in $(pwd) \033[0m\n";
fi;

# Bugsnag deploy notifications (Laravel only)
apikey=$(grep BUGSNAG_API_KEY .env|cut -d'=' -f2)
stage=$(grep APP_ENV .env|cut -d'=' -f2)
revision=$(git rev-parse HEAD)
branch=$(git rev-parse --abbrev-ref HEAD)
repo=$(basename $(pwd))
tag=$(git describe --tags|cut -d'-' -f-2|tr '-' '.')
curl https://notify.bugsnag.com/deploy -X POST -d "apiKey=${apikey}&releaseStage=${stage}&repository=https://github.com/Stidner/${repo}&revision=${revision}&branch=\"${branch}\"&appVersion=${tag}" &
