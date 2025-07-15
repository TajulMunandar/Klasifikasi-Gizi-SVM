from sklearn.model_selection import train_test_split
from sklearn.svm import SVC
from sklearn.metrics import classification_report, confusion_matrix
from sklearn.metrics import accuracy_score
import pandas as pd
import requests
import json
import joblib


def fetch_data_from_laravel(api_url):
    response = requests.get(api_url)
    data = response.json()
    return pd.DataFrame(data)


def train_model(api_url):
    df = fetch_data_from_laravel(api_url)

    features = [
        "jenis_kelamin",
        "usia_bulan",
        "berat",
        "tinggi",
        "zs_bb_u",
        "zs_tb_u",
        "zs_bb_tb",
    ]
    X = df[features]
    y = df["label_gizi"]
    names = df["nama"]

    X_train, X_test, y_train, y_test = train_test_split(
        X, y, test_size=0.3, random_state=42, stratify=y
    )

    model = SVC(kernel="linear", class_weight="balanced", probability=True)
    model.fit(X_train, y_train)

    joblib.dump(model, "flask/model_svc.pkl")
    print("Model berhasil disimpan ke 'model_svc.pkl'")

    y_pred = model.predict(X_test)
    probs = model.predict_proba(X_test)

    label_map = {
        0: "Gizi Baik",
        1: "Gizi Kurang",
        2: "Gizi Buruk",
        3: "Risiko Gizi Lebih",
    }

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
    report = classification_report(
        y_test,
        y_pred,
        zero_division=0,
        target_names=list(label_map.values()),
        output_dict=True,
    )

    accuracy = accuracy_score(y_test, y_pred)

    cm = confusion_matrix(y_test, y_pred)
    cm_list = cm.tolist()  # Convert numpy array to list for JSON serialization
    print(cm_list)
    # Kirim hasil ke Laravel
    try:
        response = requests.post(
            "http://localhost:8000/api/classifications",
            json={"hasil": result, "evaluasi": report, "confusion_matrix": cm_list},
        )
        print("Hasil dikirim ke Laravel:", response.status_code, response.text)
    except Exception as e:
        print("Gagal kirim hasil ke Laravel:", str(e))

    return {
        "hasil": result,
        "evaluasi": report,
        "confusion_matrix": cm_list,
        "accuracy": accuracy,
    }
