# Shift Parser

## Installation

1. Clone the repo via git: `git clone https://github.com/ChadSikorra/wiw-coding-assessment.git`
2. Navigate into cloned repo and install the dependencies via composer: `cd wiw-coding-assessment && composer install`
3. Run the script that outputs the JSON results (outputs to STDOUT, not a file): `php bin/shift-parser.php`

# Layout of Code / Tests.

All the code I wrote myself is located under the `src/` directory. All of my tests are under the `tests/` directory.

## What I would improve if I had more time

Given the timebox of 3 hours, there's a lot that still can be improved on this. I wasn't able to fully complete some of
standard requirements:

1. Shifts that cross midnight. More care would be needed in the code to split and calculate these properly between weekly data.
2. I believe I got the overlapping logic for invalid shifts correct, but I would've liked to add more tests cases around it.
3. I did not get to the bonus for CST to CDT transition for shift calculations.

Other things I would have liked to add:

1. Would have liked to double-check my datetime format validation. The instructions specify RFC3339, but I found that did always seem to be the case. I would've liked to investigate this further.
2. Given the limited time to work on this, many tests are missing. I tried to at least cover some of the more important aspects where I was able to.
3. Handling parsing the result set data with a streamer / generator and not just consuming it all in one go. This would need more thought about general design to support streaming JSON data and outputting results.
4. Adding options to the script file to only output data for a specific employee ID or only for data in a certain time frame.
5. Rethinking the layout of classes I created / if the structure is really ideal. The class structure evolved as I re-read / worked on requirements and organized things in my head.
