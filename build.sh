#!/usr/bin/env bash

VERSION="0.0.1";
FINAL_NAME="dist/build-${VERSION}.zip";

zip -r ${FINAL_NAME} composer.json example.php src;
cd dist;
rm latest.zip;
ln -s ${FINAL_NAME} latest.zip;
