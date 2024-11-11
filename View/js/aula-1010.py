import cv2
import numpy as np
import matplotlib.pyplot as plt

# Carregar a imagem com ru�do
imagem = cv2.imread('ruido.jpg', 0)  # Carregar a imagem em escala de cinza

# Filtro de M�dia
imagem_media = cv2.blur(imagem, (5, 5))

# Filtro Gaussiano
imagem_gaussiana = cv2.GaussianBlur(imagem, (5, 5), 0)

# Filtro de Mediana
imagem_mediana = cv2.medianBlur(imagem, 5)

# Filtro de M�ximo (Dilata��o)
kernel = np.ones((5, 5), np.uint8)
imagem_maximo = cv2.dilate(imagem, kernel)

# Filtro de M�nimo (Eros�o)
imagem_minimo = cv2.erode(imagem, kernel)

# Exibir todas as imagens para an�lise
plt.figure(figsize=(15, 10))

# Imagem original
plt.subplot(2, 3, 1)
plt.imshow(imagem, cmap='gray')
plt.title('Imagem Original')

# Filtro de M�dia
plt.subplot(2, 3, 2)
plt.imshow(imagem_media, cmap='gray')
plt.title('Filtro de M�dia')

# Filtro Gaussiano
plt.subplot(2, 3, 3)
plt.imshow(imagem_gaussiana, cmap='gray')
plt.title('Filtro Gaussiano')

# Filtro de Mediana
plt.subplot(2, 3, 4)
plt.imshow(imagem_mediana, cmap='gray')
plt.title('Filtro de Mediana')

# Filtro de M�ximo
plt.subplot(2, 3, 5)
plt.imshow(imagem_maximo, cmap='gray')
plt.title('Filtro de M�ximo')

# Filtro de M�nimo
plt.subplot(2, 3, 6)
plt.imshow(imagem_minimo, cmap='gray')
plt.title('Filtro de M�nimo')

plt.tight_layout()
plt.show()

# An�lise dos resultados
print("An�lise dos resultados:")
print("1. Filtro de M�dia: Suaviza bem, mas tende a borrar bordas e detalhes.")
print("2. Filtro Gaussiano: Suaviza de forma controlada, preservando um pouco mais as bordas.")
print("3. Filtro de Mediana: Excelente para remover ru�do 'sal e pimenta', preserva bem as bordas.")
print("4. Filtro de M�ximo: Real�a �reas mais claras, pode ser �til para remover ru�dos escuros.")
print("5. Filtro de M�nimo: Real�a �reas escuras, pode remover ru�dos claros.")

# Determina��o do melhor e pior filtro
print("\nConclus�o:")
print("O melhor filtro para remo��o de ru�do sem perder detalhes � o Filtro de Mediana, devido � sua efic�cia em preservar bordas.")
print("O pior filtro em termos de preserva��o de detalhes � o Filtro de M�dia, que borra as bordas e compromete a nitidez.")
