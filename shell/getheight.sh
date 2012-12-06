#!/bin/sh

ffprobe -show_streams -pretty -i "${1}" 2>/dev/null | grep "height=" | cut -d'=' -f2
