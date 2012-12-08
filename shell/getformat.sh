#!/bin/sh

ffprobe -pretty -show_format -i "${1}" 2>&1
