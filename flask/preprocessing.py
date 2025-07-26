# Import library yang dibutuhkan
import pandas as pd
import requests
import re


# Fungsi utama untuk mengambil data dari API dan melakukan preprocessing
def fetch_and_preprocess_data(api_url):
    # Ambil data dari API Laravel
    response = requests.get(api_url)
    data = response.json()

    # Ubah data JSON menjadi DataFrame pandas
    df = pd.DataFrame(data)

    # Fungsi untuk mengubah format usia dari "X Tahun - Y Bulan" menjadi total bulan
    def parse_age(age_str):
        match = re.match(r"(\d+) Tahun - (\d+) Bulan", str(age_str))
        if match:
            years = int(match.group(1))
            months = int(match.group(2))
            return years * 12 + months
        return None  # Jika format tidak sesuai

    # Tambahkan kolom usia dalam bulan
    df["usia_bulan"] = pd.to_numeric(df["Usia Saat Ukur"], errors="coerce")

    # Konversi kolom numerik dari string ke float, jika gagal beri NaN
    df["zs_bb_u"] = pd.to_numeric(df["ZS BB/U"], errors="coerce")
    df["zs_tb_u"] = pd.to_numeric(df["ZS TB/U"], errors="coerce")
    df["zs_bb_tb"] = pd.to_numeric(df["ZS BB/TB"], errors="coerce")
    df["berat"] = pd.to_numeric(df["Berat"], errors="coerce")
    df["tinggi"] = pd.to_numeric(df["Tinggi"], errors="coerce")

    # Konversi jenis kelamin: L = 1 (laki-laki), P = 0 (perempuan)
    df["jenis_kelamin"] = df["JK"].apply(lambda x: 1 if x == "L" else 0)

    # Fungsi untuk mengubah label BB/TB menjadi nilai numerik klasifikasi gizi
    def label_gizi(bb_tb):
        if pd.isna(bb_tb):
            return None
        bb_tb = str(bb_tb).strip().lower()
        if bb_tb == "gizi baik":
            return 0
        elif bb_tb == "gizi kurang":
            return 1
        elif bb_tb == "gizi buruk":
            return 2
        elif bb_tb == "risiko gizi lebih":
            return 3
        elif bb_tb == "-":
            return None  # Bisa diubah jadi return 4 jika ingin kategorikan unknown
        else:
            return None  # Label tidak dikenali

    # Tambahkan kolom label klasifikasi gizi
    df["label_gizi"] = df["BB/TB"].apply(label_gizi)
    print("Cek NaN per kolom:")
    print(
        df[
            [
                "usia_bulan",
                "zs_bb_u",
                "zs_tb_u",
                "zs_bb_tb",
                "berat",
                "tinggi",
                "label_gizi",
                "jenis_kelamin",
                "Nama",
            ]
        ]
        .isna()
        .sum()
    )
    # Hapus baris yang memiliki data penting yang kosong (NaN)
    df = df.dropna(
        subset=[
            "usia_bulan",
            "zs_bb_u",
            "zs_tb_u",
            "zs_bb_tb",
            "berat",
            "tinggi",
            "label_gizi",
            "jenis_kelamin",
            "Nama",  # pastikan nama tidak kosong juga
        ]
    )

    # Pilih dan ubah nama kolom untuk dikirim kembali ke Laravel
    data_to_save = df[
        [
            "Nama",
            "jenis_kelamin",
            "usia_bulan",
            "berat",
            "tinggi",
            "zs_bb_u",
            "zs_tb_u",
            "zs_bb_tb",
            "label_gizi",
        ]
    ].rename(columns={"Nama": "nama"})
    print("DataFrame sebelum dropna:", data_to_save)

    # Ubah DataFrame menjadi list of dictionaries (records) agar bisa dikirim via API
    return data_to_save.to_dict(orient="records")
