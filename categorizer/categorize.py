import os
import mysql.connector
import pandas as pd
from sentence_transformers import SentenceTransformer
import umap
import hdbscan

# ---- DB CONFIG ----
conn = mysql.connector.connect(
    host=os.environ["DB_HOST"],
    user=os.environ["DB_USER"],
    password=os.environ["DB_PASSWORD"],
    database=os.environ["DB_NAME"]
)

# ---- LOAD DATA ----
df = pd.read_sql(
    "SELECT id, title FROM photos WHERE title IS NOT NULL",
    conn
)

df["title"] = df["title"].str.strip().str.lower()

print(f"Loaded {len(df)} titles")

# ---- EMBEDDINGS ----
model = SentenceTransformer("all-MiniLM-L6-v2")

embeddings = model.encode(
    df["title"].tolist(),
    batch_size=64,
    show_progress_bar=True
)

# ---- REDUCTION ----
reduced = umap.UMAP(
    n_neighbors=20,
    n_components=5,
    min_dist=0.0,
    metric="cosine",
    random_state=42
).fit_transform(embeddings)

# ---- CLUSTERING ----
clusterer = hdbscan.HDBSCAN(
    min_cluster_size=20,
    min_samples=10,
    metric="euclidean"
)

df["cluster_id"] = clusterer.fit_predict(reduced)

# ---- WRITE BACK ----
cursor = conn.cursor()

for _, row in df.iterrows():
    cursor.execute(
        """
        UPDATE photos
        SET cluster_id = %s
        WHERE id = %s
        """,
        (int(row.cluster_id), int(row.id))
    )

conn.commit()
cursor.close()
conn.close()

print("Clustering complete")
