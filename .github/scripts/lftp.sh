#!/usr/bin/env bash
#
# Runs one lftp command against the deploy target.
#
# Split out of the workflow so the credential handling exists once: the
# password, when there is one, is passed through the environment rather than on
# a command line other processes on the runner could read.
set -euo pipefail

command="$1"
port="${SFTP_PORT:-22}"

ssh_options="-o StrictHostKeyChecking=$([ "${HOST_KEY_CHECKING:-no}" = "yes" ] && echo yes || echo no)"
ssh_options="$ssh_options -o UserKnownHostsFile=$HOME/.ssh/known_hosts"

if [ "${AUTH:-password}" = "key" ]; then
  ssh_options="$ssh_options -i $HOME/.ssh/deploy_key -o IdentitiesOnly=yes"
  credentials="-u $SFTP_USER,"
else
  credentials="-u $SFTP_USER,$SFTP_PASSWORD"
fi

exec lftp -c "
  set sftp:connect-program 'ssh -a -x $ssh_options';
  set net:max-retries 2;
  set net:timeout 20;
  set xfer:clobber on;
  open $credentials sftp://$SFTP_HOST:$port;
  $command;
  bye
"
