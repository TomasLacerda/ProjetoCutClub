import cv2
import numpy as np
import matplotlib.pyplot as plt

# Carregar a imagem com ruído
imagem = cv2.imread('ruido.jpg', 0)  # Carregar a imagem em escala de cinza

# Filtro de Média
imagem_media = cv2.blur(imagem, (5, 5))

# Filtro Gaussiano
imagem_gaussiana = cv2.GaussianBlur(imagem, (5, 5), 0)

# Filtro de Mediana
imagem_mediana = cv2.medianBlur(imagem, 5)

# Filtro de Máximo (Dilatação)
kernel = np.ones((5, 5), np.uint8)
imagem_maximo = cv2.dilate(imagem, kernel)

# Filtro de Mínimo (Erosão)
imagem_minimo = cv2.erode(imagem, kernel)

# Exibir todas as imagens para análise
plt.figure(figsize=(15, 10))

# Imagem original
plt.subplot(2, 3, 1)
plt.imshow(imagem, cmap='gray')
plt.title('Imagem Original')

# Filtro de Média
plt.subplot(2, 3, 2)
plt.imshow(imagem_media, cmap='gray')
plt.title('Filtro de Média')

# Filtro Gaussiano
plt.subplot(2, 3, 3)
plt.imshow(imagem_gaussiana, cmap='gray')
plt.title('Filtro Gaussiano')

# Filtro de Mediana
plt.subplot(2, 3, 4)
plt.imshow(imagem_mediana, cmap='gray')
plt.title('Filtro de Mediana')

# Filtro de Máximo
plt.subplot(2, 3, 5)
plt.imshow(imagem_maximo, cmap='gray')
plt.title('Filtro de Máximo')

# Filtro de Mínimo
plt.subplot(2, 3, 6)
plt.imshow(imagem_minimo, cmap='gray')
plt.title('Filtro de Mínimo')

plt.tight_layout()
plt.show()

# Análise dos resultados
print("Análise dos resultados:")
print("1. Filtro de Média: Suaviza bem, mas tende a borrar bordas e detalhes.")
print("2. Filtro Gaussiano: Suaviza de forma controlada, preservando um pouco mais as bordas.")
print("3. Filtro de Mediana: Excelente para remover ruído 'sal e pimenta', preserva bem as bordas.")
print("4. Filtro de Máximo: Realça áreas mais claras, pode ser útil para remover ruídos escuros.")
print("5. Filtro de Mínimo: Realça áreas escuras, pode remover ruídos claros.")

# Determinação do melhor e pior filtro
print("\nConclusão:")
print("O melhor filtro para remoção de ruído sem perder detalhes é o Filtro de Mediana, devido à sua eficácia em preservar bordas.")
print("O pior filtro em termos de preservação de detalhes é o Filtro de Média, que borra as bordas e compromete a nitidez.")
