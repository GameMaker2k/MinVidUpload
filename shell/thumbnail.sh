#!/bin/sh

scriptdir="$(dirname "${0}")"
vidwidth="$(${scriptdir}/getwidth.sh "${1}")"
vidheight="$(${scriptdir}/getheight.sh "${1}")"
vidresolution="${vidwidth}x${vidheight}"
ThumbNailDIR="$(realpath $(dirname "${0}")"/../thumbnail")"
ThumbNamePNG="${ThumbNailDIR}/$(basename "${1%.*}.png")"
ThumbNameGIF="${ThumbNailDIR}/$(basename "${1%.*}.gif")"
if [ -z ${3} ]; then 
    timeoffset=4;
    echo ${timeoffset}
else
    timeoffset=${3};
    echo ${timeoffset}
fi
if [ ! -f "${ThumbNamePNG}" ]; then
    ffmpeg -sameq -ss ${timeoffset} -i "${1}" -vcodec png -vframes 1 -an -f rawvideo -s $vidresolution -y "$ThumbNamePNG"
    # ffmpeg -sameq -itsoffset -${timeoffset} -vf fps=fps=1/60 -i "${1}" -vcodec gif -vframes 10 -an -f rawvideo -s $vidresolution -y "$ThumbNameGIF"
fi
