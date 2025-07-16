# Import library yang diperlukan
from sklearn.model_selection import train_test_split
from sklearn.svm import SVC
from sklearn.metrics import classification_report, confusion_matrix, accuracy_score
import pandas as pd
import requests
import joblib
import json


# Fungsi untuk mengambil data training dari Laravel API
def fetch_data_from_laravel(api_url):
    response = requests.get(api_url)
    data = response.json()
    return pd.DataFrame(data)


# Fungsi utama untuk training model dan mengirim hasil evaluasi ke Laravel
def train_model(api_url):
    # Ambil data dari Laravel
    df = fetch_data_from_laravel(api_url)

    # Tentukan fitur dan target
    features = [
        "jenis_kelamin",
        "usia_bulan",
        "berat",
        "tinggi",
        "zs_bb_u",
        "zs_tb_u",
        "zs_bb_tb",
    ]
    X = df[features]  # Fitur input
    y = df["label_gizi"]  # Target label
    names = df["nama"]  # Untuk menampilkan nama anak saat evaluasi

    # Bagi data menjadi data latih dan data uji (70:30)
    X_train, X_test, y_train, y_test = train_test_split(
        X, y, test_size=0.3, random_state=42, stratify=y
    )

    # Inisialisasi dan latih model SVM
    model = SVC(kernel="linear", class_weight="balanced", probability=True)
    model.fit(X_train, y_train)

    # Simpan model yang sudah dilatih ke file .pkl
    joblib.dump(model, "flask/model_svc.pkl")
    print("Model berhasil disimpan ke 'model_svc.pkl'")

    # Lakukan prediksi terhadap data uji
    y_pred = model.predict(X_test)
    probs = model.predict_proba(X_test)  # Probabilitas tiap kelas

    # Mapping label numerik ke label string
    label_map = {
        0: "Gizi Baik",
        1: "Gizi Kurang",
        2: "Gizi Buruk",
        3: "Risiko Gizi Lebih",
    }

    # Siapkan data hasil prediksi
    result = []
    for idx, pred, prob in zip(X_test.index, y_pred, probs):
        nama = names.loc[idx]
        max_prob = float(max(prob))  # Ambil probabilitas tertinggi
        result.append(
            {
                "nama": nama,
                "prediksi": label_map.get(pred, "Tidak Dikenal"),
                "probabilitas": round(max_prob, 4),
            }
        )

    # Buat laporan klasifikasi (precision, recall, f1, dll)
    report = classification_report(
        y_test,
        y_pred,
        zero_division=0,
        target_names=list(label_map.values()),
        output_dict=True,
    )

    # Hitung akurasi
    accuracy = accuracy_score(y_test, y_pred)

    # Hitung confusion matrix
    cm = confusion_matrix(y_test, y_pred)
    cm_list = cm.tolist()  # Ubah ke list agar bisa dikirim ke JSON

    print("Confusion Matrix:", cm_list)

    # Kirim hasil evaluasi dan prediksi ke API Laravel
    try:
        response = requests.post(
            "http://localhost:8000/api/classifications",
            json={
                "hasil": result,
                "evaluasi": report,
                "confusion_matrix": cm_list,
            },
        )
        print("Hasil dikirim ke Laravel:", response.status_code, response.text)
    except Exception as e:
        print("Gagal kirim hasil ke Laravel:", str(e))

    # Kembalikan hasil sebagai dictionary (bisa digunakan oleh Flask API)
    return {
        "hasil": result,
        "evaluasi": report,
        "confusion_matrix": cm_list,
        "accuracy": accuracy,
    }
