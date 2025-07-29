import sys, io, base64, json
import matplotlib.pyplot as plt
import numpy as np

# Leer JSON desde CLI
raw = sys.argv[1]
params = json.loads(raw)
titulo = params["titulo"]
categorias = params["categorias"]
valores = params["valores"]

colores = ["#3c78d8", "#93c47d", "#ffd966", "#e06666", "#76a5af", "#8e7cc3"]

fig, ax = plt.subplots(figsize=(10, 3.5))
bar_positions = np.arange(len(categorias))
bars = ax.bar(bar_positions, valores, width=0.5, color=colores, edgecolor='black')

# Sombra lateral simulada
for i, bar in enumerate(bars):
    x = bar.get_x() + bar.get_width()
    height = bar.get_height()
    ax.bar(x, height, width=0.05, bottom=0, color="gray", zorder=0)

# Texto en la parte superior
for i, bar in enumerate(bars):
    height = bar.get_height()
    ax.text(bar.get_x() + bar.get_width()/2, height + (height * 0.01), f"{valores[i]:,}", ha='center', fontsize=8, weight='bold')

ax.set_title(titulo, fontsize=14, weight='bold')
ax.set_ylabel("AMENAZAS MITIGADAS", fontsize=10)
ax.set_xticks(bar_positions)
ax.set_xticklabels(categorias, fontsize=8)
ax.set_yticklabels([])
ax.grid(axis='y', linestyle='--', alpha=0.5)
plt.tight_layout()
plt.box(False)

buf = io.BytesIO()
plt.savefig(buf, format='png', dpi=150, bbox_inches='tight')
buf.seek(0)
print(base64.b64encode(buf.read()).decode('utf-8'))
