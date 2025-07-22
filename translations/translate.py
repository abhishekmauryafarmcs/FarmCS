import json
from deep_translator import GoogleTranslator

def translate_text(text, target_lang):
    try:
        translator = GoogleTranslator(source='auto', target=target_lang)
        translation = translator.translate(text)
        return translation
    except Exception as e:
        print(f"Error translating text: {e}")
        return text

def create_translations():
    # Define Indian languages to translate to
    languages = {
        'hi': 'Hindi',
        'bn': 'Bengali',
        'te': 'Telugu', 
        'ta': 'Tamil',
        'mr': 'Marathi',
        'gu': 'Gujarati',
        'kn': 'Kannada',
        'ml': 'Malayalam',
        'pa': 'Punjabi',
        'or': 'Odia',
        'bho': 'Bhojpuri'
    }

    # Comprehensive text content to translate
    content = {
        "nav": {
            "home": "Home",
            "features": "Features",
            "cropdata": "Crop Data",
            "learn-more": "Learn More",
            "about": "About Us",
            "contact": "Contact",
            "login": "Login",
            "signup": "Sign Up"
        },
        "hero": {
            "title": "Empowering Indian Farmers with Smart Irrigation Solutions",
            "subtitle": "Save water, improve yields, and embrace innovation in agriculture with India's first smart sprinkler system.",
            "cta_button": "Learn More"
        },
        "features": {
            "title": "Smart Features for Modern Farming",
            "subtitle": "Discover how FarmCS revolutionizes irrigation",
            "real-time-data": "Real-Time Field Data",
            "real-time-data-description": "Monitor soil moisture, temperature, and humidity in real-time",
            "smart-fertilization": "Smart Fertilization",
            "smart-fertilization-description": "Optimize nutrient delivery with intelligent fertigation control",
            "fire-detection": "Fire Detection",
            "fire-detection-description": "Early warning system for fire prevention and control",
            "bird-deterrence": "Bird Deterrence",
            "bird-deterrence-description": "Protect your crops with smart bird control system"
        },
        "metrics": {
            "water-saved": "0",
            "water-saved-description": "Million Liters of Water Saved",
            "farmers-count": "0",
            "farmers-count-description": "Farmers Using FarmCS",
            "acres-covered": "0",
            "acres-covered-description": "Acres Covered"
        },
        "testimonials": {
            "title": "Success Stories",
            "subtitle": "Hear from farmers who transformed their irrigation with FarmCS",
            "farmer1-name": "Rajesh Kumar",
            "farmer1-testimonial": "\"FarmCS helped me reduce water usage by 40% while improving my crop yield. The real-time monitoring is a game-changer!\"",
            "farmer2-name": "Priya Patel",
            "farmer2-testimonial": "\"The smart fertilization feature has made a huge difference in my farm's productivity. My profits have increased significantly.\"",
            "farmer3-name": "Suresh Singh",
            "farmer3-testimonial": "\"The fire detection system saved my wheat field last summer. FarmCS is truly a revolutionary product for Indian farmers.\""
        },
        "footer": {
            "description": "Revolutionizing agriculture through smart irrigation and IoT technology. Making farming smarter, sustainable, and more efficient.",
            "features": {
                "title": "Our Features",
                "smart-irrigation": "Smart Irrigation",
                "soil-monitoring": "Soil Monitoring",
                "weather-integration": "Weather Integration",
                "mobile-control": "Mobile Control",
                "data-analytics": "Data Analytics"
            },
            "resources": {
                "title": "Resources",
                "documentation": "Documentation",
                "support-center": "Support Center",
                "installation-guide": "Installation Guide",
                "system-updates": "System Updates",
                "user-manual": "User Manual"
            },
            "contact": {
                "title": "Contact & Legal",
                "contact-us": "Contact Us",
                "privacy-policy": "Privacy Policy",
                "terms-of-service": "Terms of Service",
                "warranty-info": "Warranty Info",
                "support-policy": "Support Policy"
            },
            "social": {
                "title": "Connect With Us",
                "facebook": "Facebook",
                "twitter": "Twitter", 
                "linkedin": "LinkedIn",
                "instagram": "Instagram"
            },
            "copyright": " 2023 FarmCS. All rights reserved.",
            "made-with-love": "Made with in India"
        }
    }

    # Create translations for each language
    translations = {}
    for lang_code, lang_name in languages.items():
        print(f"Translating to {lang_name}...")
        translations[lang_code] = {}
        
        for section, items in content.items():
            translations[lang_code][section] = {}
            if isinstance(items, dict):
                for key, text in items.items():
                    # Skip translating numbers, icons, or already translated items
                    if not isinstance(text, str) or len(text) < 2 or text.startswith('"'):
                        translations[lang_code][section][key] = text
                    else:
                        translations[lang_code][section][key] = translate_text(text, lang_code)
            else:
                translations[lang_code][section] = translate_text(items, lang_code)

    # Save translations to JSON file
    with open('translations.json', 'w', encoding='utf-8') as f:
        json.dump(translations, f, ensure_ascii=False, indent=4)

if __name__ == "__main__":
    create_translations()
