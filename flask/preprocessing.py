import pandas as pd
import requests
import re


def fetch_and_preprocess_data(api_url):
    response = requests.get(api_url)
    data = response.json()
    df = pd.DataFrame(data)
    print("DataFrame sebelum dropna:")
    print(
        df[
            [
                "Nama",
                "Usia Saat Ukur",
                "ZS BB/U",
                "ZS TB/U",
                "ZS BB/TB",
                "Berat",
                "Tinggi",
                "JK",
                "BB/TB",
            ]
        ]
    )

    def parse_age(age_str):
        match = re.match(r"(\d+) Tahun - (\d+) Bulan", str(age_str))
        if match:
            years = int(match.group(1))
            months = int(match.group(2))
            return years * 12 + months
        return None

    df["usia_bulan"] = df["Usia Saat Ukur"].apply(parse_age)
    df["zs_bb_u"] = pd.to_numeric(df["ZS BB/U"], errors="coerce")
    df["zs_tb_u"] = pd.to_numeric(df["ZS TB/U"], errors="coerce")
    df["zs_bb_tb"] = pd.to_numeric(df["ZS BB/TB"], errors="coerce")
    df["berat"] = pd.to_numeric(df["Berat"], errors="coerce")
    df["tinggi"] = pd.to_numeric(df["Tinggi"], errors="coerce")
    df["jenis_kelamin"] = df["JK"].apply(lambda x: 1 if x == "L" else 0)

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
            return None  # atau bisa juga return 4 untuk kategori unknown
        else:
            return None  # label tidak dikenali

    df["label_gizi"] = df["BB/TB"].apply(label_gizi)

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
            "Nama",
        ]
    )

    # Pilih kolom untuk dikirim ke Laravel
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

    return data_to_save.to_dict(orient="records")
