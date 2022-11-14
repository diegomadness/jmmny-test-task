# Backend Take-home Task
## Looking back at the code
1. There is no failsafe mechanism to ensure that all silence_start blocks will have a silence_end block for them since the files I have for this task are clear(I've checked). If there would be even a small chance that it is not true, I would certainly add some checks like "is the syntax consistent
across whole file", "is there a silence_end for every silence_start" and "are there problematic symbols like line breaks or empty spaces at the start and the end of the file". Right now these checks seem redundant.

2. Structured arrays like `$dialogData` that are part of `DialogService` in the real application would became a `Dialog` model

## How to run the application
    php index.php