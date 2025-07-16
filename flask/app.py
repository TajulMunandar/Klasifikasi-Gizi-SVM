# Import library yang dibutuhkan
from flask import Flask, jsonify, make_response, request
from flask_cors import CORS
from preprocessing import fetch_and_preprocess_data
from training import train_model
import requests
import joblib

# Inisialisasi Flask app
app = Flask(__name__)
CORS(app)  # Izinkan CORS agar API bisa diakses dari domain lain (misal React/Laravel)

# Route untuk menjalankan proses preprocessing
@app.route("/run-preprocessing")
def run_preprocessing():
    try:
        # URL API sumber dan target dari Laravel
        source_api = "http://localhost:8000/api/data-anak"
        target_api = "http://localhost:8000/api/preprocessings"

        # Ambil data dari Laravel dan lakukan preprocessing
        processed_data = fetch_and_preprocess_data(source_api)

        # Kirim data hasil preprocessing kembali ke Laravel
        response = requests.post(target_api, json=processed_data)

        print("Laravel response:", response.status_code)
        print("Response text:", response.text)

        # Cek jika berhasil dikirim
        if response.status_code == 201:
            return jsonify({"message": "Preprocessing berhasil dikirim ke database"}), 200
        else:
            return jsonify({
                "message": "Gagal mengirim data ke Laravel",
                "details": response.text,
            }), 500

    except Exception as e:
        # Tangani error jika terjadi
        print("ERROR:", str(e))
        return jsonify({
            "message": "Terjadi kesalahan saat preprocessing",
            "error": str(e)
        }), 500

# Route untuk menjalankan training model ML
@app.route("/run-training")
def run_training():
    try:
        # Endpoint API Laravel yang menyediakan data training
        api_url = "http://localhost:8000/api/train"

        # Jalankan training model dan dapatkan hasil evaluasi
        hasil = train_model(api_url)

        # Kembalikan response dengan hasil training
        return make_response(jsonify({
            "message": "Training berhasil dijalankan",
            "data": hasil["hasil"],                    # Data prediksi hasil training
            "confusion_matrix": hasil["confusion_matrix"],  # Confusion matrix
            "evaluasi": hasil["evaluasi"],              # Evaluasi (precision, recall, f1-score)
            "accuracy": hasil["accuracy"],              # Akurasi model
        }), 200)

    except Exception as e:
        # Tangani error training
        print("ERROR:", str(e))
        return make_response(jsonify({
            "message": "Gagal menjalankan training",
            "error": str(e)
        }), 500)

# Route untuk prediksi gizi anak berdasarkan input user
@app.route("/predict", methods=["POST"])
def predict():
    try:
        # Ambil data input dari request
        input_data = request.json

        # ✅ Muat model yang sudah dilatih
        model = joblib.load("model_svc.pkl")

        # Siapkan data fitur yang akan diprediksi
        features = [
            input_data["jenis_kelamin"],  # L/P -> 0/1 (pastikan preprocessing sebelumnya konsisten)
            input_data["usia_bulan"],
            input_data["berat"],
            input_data["tinggi"],
            input_data["zs_bb_u"],
            input_data["zs_tb_u"],
            input_data["zs_bb_tb"],
        ]
        X_input = [features]  # Buat array 2D untuk model input

        # Lakukan prediksi
        prediction = model.predict(X_input)[0]
        probability = model.predict_proba(X_input)[0].max()  # Ambil probabilitas tertinggi

        # Pemetaan label hasil prediksi
        label_map = {
            0: "Gizi Baik",
            1: "Gizi Kurang",
            2: "Gizi Buruk",
            3: "Risiko Gizi Lebih",
        }

        # Kembalikan hasil prediksi dan probabilitas
        return jsonify({
            "prediksi": label_map.get(prediction, "Tidak Dikenal"),
            "probabilitas": round(float(probability), 4)
        })

    except Exception as e:
        # Tangani error saat prediksi
        return jsonify({"error": str(e)}), 500

# Jalankan Flask app
if __name__ == "__main__":
    app.run(debug=True)
