from flask import Flask, jsonify, make_response, request
from flask_cors import CORS
from preprocessing import fetch_and_preprocess_data
from training import train_model
import requests
import joblib

app = Flask(__name__)
CORS(app)


@app.route("/run-preprocessing")
def run_preprocessing():
    try:
        source_api = "http://localhost:8000/api/data-anak"
        target_api = "http://localhost:8000/api/preprocessings"

        processed_data = fetch_and_preprocess_data(source_api)

        response = requests.post(target_api, json=processed_data)

        print("Laravel response:", response.status_code)
        print("Response text:", response.text)

        if response.status_code == 201:
            return (
                jsonify({"message": "Preprocessing berhasil dikirim ke database"}),
                200,
            )
        else:
            return (
                jsonify(
                    {
                        "message": "Gagal mengirim data ke Laravel",
                        "details": response.text,
                    }
                ),
                500,
            )

    except Exception as e:
        print("ERROR:", str(e))
        return (
            jsonify(
                {"message": "Terjadi kesalahan saat preprocessing", "error": str(e)}
            ),
            500,
        )


@app.route("/run-training")
def run_training():
    try:
        api_url = "http://localhost:8000/api/train"
        hasil = train_model(api_url)
        return make_response(
            jsonify(
                {
                    "message": "Training berhasil dijalankan",
                    "data": hasil["hasil"],
                    "confusion_matrix": hasil["confusion_matrix"],
                    "evaluasi": hasil["evaluasi"],  # ← Tambahkan ini
                    "accuracy": hasil["accuracy"],
                }
            ),
            200,
        )
    except Exception as e:
        print("ERROR:", str(e))
        return make_response(
            jsonify({"message": "Gagal menjalankan training", "error": str(e)}), 500
        )


@app.route("/predict", methods=["POST"])
def predict():
    try:
        # Ambil data dari request
        input_data = request.json

        # ✅ Muat model dari file
        model = joblib.load("model_svc.pkl")

        # Siapkan data dalam format array 2D
        features = [
            input_data["jenis_kelamin"],
            input_data["usia_bulan"],
            input_data["berat"],
            input_data["tinggi"],
            input_data["zs_bb_u"],
            input_data["zs_tb_u"],
            input_data["zs_bb_tb"],
        ]
        X_input = [features]

        # Prediksi
        prediction = model.predict(X_input)[0]
        probability = model.predict_proba(X_input)[0].max()

        label_map = {
            0: "Gizi Baik",
            1: "Gizi Kurang",
            2: "Gizi Buruk",
            3: "Risiko Gizi Lebih",
        }

        return jsonify(
            {
                "prediksi": label_map.get(prediction, "Tidak Dikenal"),
                "probabilitas": round(float(probability), 4),
            }
        )
    except Exception as e:
        return jsonify({"error": str(e)}), 500


if __name__ == "__main__":
    app.run(debug=True)
