#!/bin/bash
CIDR="$1"

if ! ip route | grep -q "$CIDR"; then
	echo "NO"
	exit 1
fi
exit 0