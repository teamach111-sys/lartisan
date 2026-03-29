<script>
    class ImageCompressor {
        static async compress(file, maxWidth = 1200, quality = 0.7) {
            return new Promise((resolve, reject) => {
                if (!file || !file.type.match(/image.*/)) {
                    resolve(file); // Not an image, just return the original file
                    return;
                }

                const img = new Image();
                const reader = new FileReader();

                reader.onload = (e) => {
                    img.onload = () => {
                        const canvas = document.createElement('canvas');
                        let width = img.width;
                        let height = img.height;

                        if (width > maxWidth) {
                            height = Math.round(height * (maxWidth / width));
                            width = maxWidth;
                        }

                        canvas.width = width;
                        canvas.height = height;

                        const ctx = canvas.getContext('2d');
                        ctx.fillStyle = '#FFFFFF';
                        ctx.fillRect(0, 0, width, height);
                        ctx.drawImage(img, 0, 0, width, height);

                        canvas.toBlob((blob) => {
                            if (blob) {
                                const compressedFile = new File([blob], file.name.replace(/\.[^/.]+$/, "") + ".jpg", {
                                    type: 'image/jpeg',
                                    lastModified: Date.now(),
                                });
                                resolve(compressedFile);
                            } else {
                                reject(new Error('Canvas to Blob failed.'));
                            }
                        }, 'image/jpeg', quality);
                    };
                    img.onerror = () => reject(new Error('Failed to load image.'));
                    img.src = e.target.result;
                };
                reader.onerror = () => reject(new Error('Failed to read file.'));
                reader.readAsDataURL(file);
            });
        }

        static replaceFileInput(inputElement, newFile) {
            try {
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(newFile);
                inputElement.files = dataTransfer.files;
                return true;
            } catch (e) {
                console.error("DataTransfer not supported by this browser.", e);
                return false;
            }
        }
    }
    
    // Rendre disponible globalement
    window.ImageCompressor = ImageCompressor;
</script>
