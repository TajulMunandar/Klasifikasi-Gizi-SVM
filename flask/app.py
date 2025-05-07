import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.svm import SVC
from sklearn.metrics import classification_report
import re

# Baca data
df = pd.read_csv("flask/data_real.csv")


# Konversi usia ke bulan
def parse_age(age_str):
    match = re.match(r"(\d+) Tahun - (\d+) Bulan", str(age_str))
    if match:
        years = int(match.group(1))
        months = int(match.group(2))
        return years * 12 + months
    return None


df["Usia Bulan"] = df["Usia Saat Ukur"].apply(parse_age)

# Konversi kolom numerik
df["ZS BB/U"] = pd.to_numeric(df["ZS BB/U"], errors="coerce")
df["ZS TB/U"] = pd.to_numeric(df["ZS TB/U"], errors="coerce")
df["ZS BB/TB"] = pd.to_numeric(df["ZS BB/TB"], errors="coerce")
df["Berat"] = pd.to_numeric(df["Berat"], errors="coerce")
df["Tinggi"] = pd.to_numeric(df["Tinggi"], errors="coerce")

# Tambahkan jenis kelamin ke dalam fitur
# Misalkan "Jenis Kelamin" berisi 'L' untuk laki-laki dan 'P' untuk perempuan
df["Jenis Kelamin"] = df["JK"].apply(lambda x: 1 if x == "L" else 0)


# Buat label dari kolom "BB/U"
def label_gizi(bb_label):
    if pd.isna(bb_label):
        return None
    bb_label = str(bb_label).strip().lower()
    return 0 if "gizi baik" in bb_label else 1


df["Label"] = df["BB/TB"].apply(label_gizi)

# Pilih fitur yang relevan
features = [
    "Usia Bulan",
    "Berat",
    "Tinggi",
    "ZS BB/U",
    "ZS TB/U",
    "ZS BB/TB",
    "Jenis Kelamin",
]
df[features] = df[features].apply(pd.to_numeric, errors="coerce")

# Hapus baris dengan nilai kosong
df = df.dropna(subset=features + ["Label", "Nama"])

# Pisahkan fitur dan label
X = df[features]
y = df["Label"]

# Split data
X_train, X_test, y_train, y_test = train_test_split(
    X, y, test_size=0.3, random_state=42, stratify=y
)

# Latih model
model = SVC(kernel="linear", class_weight="balanced")
model.fit(X_train, y_train)

# Prediksi dan evaluasi
y_pred = model.predict(X_test)

# Tampilkan hasil
print("\nNama\t\t\tStatus Gizi")
for idx, pred in zip(X_test.index, y_pred):
    nama = df.loc[idx, "Nama"]
    status = "Kurang Gizi" if pred == 1 else "Gizi Baik"
    print(f"{nama}\t{status}")

# Laporan klasifikasi
print(
    "\nLaporan Klasifikasi:\n", classification_report(y_test, y_pred, zero_division=0)
)
