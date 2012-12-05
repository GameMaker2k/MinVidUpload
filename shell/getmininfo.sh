#!/bin/sh

ffprobe -pretty -i "$1" 2>&1
