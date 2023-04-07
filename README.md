# Iran Prayer Times

This Python script retrieves and displays the daily prayer times for a given city in Iran in English. It does so by scraping the website [tala.ir](https://www.tala.ir/prayer-times/) using the `requests` and `beautifulsoup4` libraries.

## Table of Contents

- [Installation](#installation)
- [Usage](#usage)
- [Example Output](#example-output)
- [License](#license)

## Installation

1. Clone this repository to your local machine.
2. Install the required packages by running `pip install -r requirements.txt` in your command prompt or terminal.

## Usage

1. Run the script by typing `python prayer_times.py` in your command prompt or terminal.
2. When prompted, enter the name of the city in Iran for which you want to see the prayer times. The city name should be entered in Persian letters.
3. The script will retrieve the prayer times from tala.ir and display them in English in a tabular format.

## Example Output

Enter the name of your city in Persian: تهران

┌───────────────────┬───────────────┐
│ Prayer            │ Time          │
├───────────────────┼───────────────┤
│ Morning Azan      │    05:10      │
│ Sunrise           │    06:33      │
│ Noon Azan         │    13:02      │
│ Sunset            │    19:11      │
│ Maghrib Azan      │    19:31      │
│ Islamic midnight  │    23:04      │
└───────────────────┴───────────────┘



Note: This script may not provide accurate or up-to-date information about prayer times in Iran. Please verify the information provided with a reliable source before making any religious observances.

## License

This project is licensed under the GNU General Public License v3.0 License - see the [LICENSE](https://github.com/ALIILAPRO/Iran-Prayer-Times/blob/main/LICENSE) file for details.

