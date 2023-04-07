import requests
from bs4 import BeautifulSoup
from urllib.parse import quote

def persian_num_to_english(text):
    persian_chars = {
        '۰': '0', '۱': '1', '۲': '2', '۳': '3', '۴': '4',
        '۵': '5', '۶': '6', '۷': '7', '۸': '8', '۹': '9'
    }
    
    english_text = ''
    
    for char in text:
        if char in persian_chars:
            english_text += persian_chars[char]
        else:
            english_text += char
    
    return english_text


def persian_prayer_to_english(prayer_name):
    persian_to_english_dict = {
        'اذان صبح': 'Morning Azan',
        'طلوع آفتاب': 'Sunrise',
        'اذان ظهر': 'Noon Azan',
        'غروب آفتاب': 'Sunset',
        'اذان مغرب': 'Maghrib Azan',
        'نیمه شب شرعی': 'Islamic midnight'
    }
    
    return persian_to_english_dict.get(prayer_name, prayer_name)


try:
    location = input('Enter the name of your city in Persian: ')
    encoded_location = quote(location)

    url = f'https://www.tala.ir/prayer-times/{encoded_location}'
    response = requests.get(url)
    response.raise_for_status()

    soup = BeautifulSoup(response.content, 'html.parser')
    prayer_times_div = soup.find('div', class_='left')

    prayer_times = {}
    for div in prayer_times_div.find_all('div'):
        prayer_name, prayer_time = div.text.strip().split(': ')
        prayer_times[prayer_name] = prayer_time

    english_prayer_times = {}
    for prayer_name, prayer_time in prayer_times.items():
        english_prayer_name = persian_prayer_to_english(prayer_name)
        english_prayer_time = persian_num_to_english(prayer_time)
        english_prayer_times[english_prayer_name] = english_prayer_time

    print('{:<20} {:<15}'.format('Prayer', 'Time'))
    print('-'*35)
    for prayer_name, prayer_time in english_prayer_times.items():
        print('{:<20} {:<15}'.format(prayer_name, prayer_time))

except requests.exceptions.RequestException as e:
    print(f"Error: {e}")    
except Exception as e:
    print(f"An error occurred: {e}")
