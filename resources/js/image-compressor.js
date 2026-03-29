/**
 * Helper class for client-side image compression.
 */
export default class ImageCompressor {
    /**
     * Compress an image file.
     * @param {File} file - The original image file
     * @param {number} maxWidth - The maximum width of the compressed image
     * @param {number} quality - The quality of the JPEG compression (0.0 to 1.0)
     * @returns {Promise<File>} A promise that resolves to the compressed File object
     */
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

                    // Calculate new dimensions if the image is wider than maxWidth
                    if (width > maxWidth) {
                        height = Math.round(height * (maxWidth / width));
                        width = maxWidth;
                    }

                    canvas.width = width;
                    canvas.height = height;

                    const ctx = canvas.getContext('2d');
                    
                    // Fill with white background in case of transparent png
                    ctx.fillStyle = '#FFFFFF';
                    ctx.fillRect(0, 0, width, height);
                    
                    ctx.drawImage(img, 0, 0, width, height);

                    canvas.toBlob((blob) => {
                        if (blob) {
                            // Create a new File object from the blob
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
                
                img.onerror = () => {
                    reject(new Error('Failed to load image.'));
                }
                
                img.src = e.target.result;
            };

            reader.onerror = () => {
                reject(new Error('Failed to read file.'));
            };

            reader.readAsDataURL(file);
        });
    }

    /**
     * Helper to safely replace a file in an input element using DataTransfer.
     * @param {HTMLInputElement} inputElement 
     * @param {File} newFile 
     */
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
