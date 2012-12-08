#!/bin/sh

ffprobe -pretty -show_streams -i "${1}" 2>&1
