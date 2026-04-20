from flask import Flask, request, jsonify
from flask_cors import CORS
from geopy.geocoders import Nominatim

app = Flask(__name__)
CORS(app)

geolocator = Nominatim(user_agent="your_app_name")

@app.route('/get_location', methods=['POST'])
def get_location():
    data = request.get_json()
    latitude = data.get('latitude')
    longitude = data.get('longitude')
    
    if latitude is None or longitude is None:
        return jsonify({"error": "Latitude and longitude required"}), 400
    
    try:
        location = geolocator.reverse((latitude, longitude), language='en')
        if location is None:
            return jsonify({"error": "Location not found"}), 404
        
        address = location.raw.get('address', {})
        neighbourhood = address.get('suburb', '') or address.get('neighbourhood', '')
        municipality = address.get('city', '') or address.get('town', '') or address.get('municipality', '')

        
        return jsonify({
            "neighbourhood": neighbourhood,
            "municipality": municipality
        })
    except Exception as e:
        return jsonify({"error": str(e)}), 500

if __name__ == '__main__':
    app.run(port=5002)

