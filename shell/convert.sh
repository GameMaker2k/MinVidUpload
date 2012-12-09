#!/bin/sh

scriptdir="$(dirname "${0}")"
VideoDIR="$(dirname "${0}")/../uploads"
VideoTMPDIR="$(dirname "${0}")/../vidtmp"
VideoLOGDIR="$(dirname "${0}")/../vidlogs"

if [ "${2}" == "flv" ]; then
    VideoOutName="$(basename "${1%.*}.flv")"
fi
if [ "${2}" == "mp4" ]; then
    VideoOutName="$(basename "${1%.*}.mp4")"
fi

if [ ! -f "${1}" ]; then
    if [ "${2}" == "flv" ]; then
        ffmpeg -i "${1}" -deinterlace -stats -sameq -vcodec flv -acodec libmp3lame -strict experimental -f flv "${VideoTMPDIR}/$(basename "${1%.*}.flv")" 1> "${VideoLOGDIR}/$(basename "${1%.*}.log")" 2>&1
        rm "${1}" "${VideoLOGDIR}/$(basename "${1%.*}.log")"
        mv "${VideoTMPDIR}/$(basename "${1%.*}.flv")" "${VideoDIR}/$(basename "${1%.*}.flv")"
    fi
    if [ "${2}" == "mp4" ]; then
        ffmpeg -i "${1}" -deinterlace -stats -sameq -vcodec libx264 -acodec aac -strict experimental -f mp4 "${VideoTMPDIR}/$(basename "${1%.*}.mp4")" 1> "${VideoLOGDIR}/$(basename "${1%.*}.log")" 2>&1
        rm "${1}" "${VideoLOGDIR}/$(basename "${1%.*}.log")"
        mv "${VideoTMPDIR}/$(basename "${1%.*}.mp4")" "${VideoDIR}/$(basename "${1%.*}.mp4")"
    fi
fi
