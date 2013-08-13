#!/bin/bash

while read oldrev newrev ref
do
    branch=`echo $ref | cut -d/ -f3`
    GIT_WORK_TREE=/path/to/local/checkout git checkout -f $branch
done