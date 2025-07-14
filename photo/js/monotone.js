// モノトーン画像変換機能
(function() {
let isMonotone = false;
let originalImages = new Map(); // 元の画像データを保存

// モノトーンボタンを作成
function createMonotoneButton() {
	const button = document.createElement('button');
	button.id = 'monotone-toggle-btn';
	button.textContent = 'モノトーン';
	button.style.cssText = `
		background-color: #333;
		color: white;
		border: none;
		padding: 8px 16px;
		margin: 0 10px;
		border-radius: 4px;
		cursor: pointer;
		font-size: 14px;
		transition: background-color 0.3s;
	`;
	
	// ホバー効果
	button.addEventListener('mouseenter', function() {
		this.style.backgroundColor = '#555';
	});
	
	button.addEventListener('mouseleave', function() {
		this.style.backgroundColor = isMonotone ? '#666' : '#333';
	});
	
	// クリックイベント
	button.addEventListener('click', toggleMonotone);
	
	return button;
}

// 画像をモノトーンに変換
function convertToMonotone(img) {
	return new Promise((resolve, reject) => {
		// 元の画像を保存
		if (!originalImages.has(img)) {
			originalImages.set(img, img.src);
		}

		const canvas = document.createElement('canvas');
		const ctx = canvas.getContext('2d');
		
		// 画像が読み込まれてから処理
		const processImage = () => {
			canvas.width = img.naturalWidth || img.width;
			canvas.height = img.naturalHeight || img.height;
			
			// 画像をcanvasに描画
			ctx.drawImage(img, 0, 0);
			
			// 画像データを取得
			const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
			const data = imageData.data;
			
			// グレースケール変換
			for (let i = 0; i < data.length; i += 4) {
				const gray = data[i] * 0.299 + data[i + 1] * 0.587 + data[i + 2] * 0.114;
				data[i] = gray;	 // R
				data[i + 1] = gray; // G
				data[i + 2] = gray; // B
				// data[i + 3] はアルファ値なのでそのまま
			}
			
			// 変換後の画像データをcanvasに戻す
			ctx.putImageData(imageData, 0, 0);
			
			// Data URLに変換
			const dataURL = canvas.toDataURL();
			img.src = dataURL;
			
			resolve();
		};
		
		if (img.complete && img.naturalWidth > 0) {
			processImage();
		} else {
			img.addEventListener('load', processImage);
			img.addEventListener('error', reject);
		}
	});
}

// 画像を元に戻す
function restoreOriginal(img) {
	if (originalImages.has(img)) {
		img.src = originalImages.get(img);
	}
}

// モノトーン切り替え
async function toggleMonotone() {
	const button = document.getElementById('monotone-toggle-btn');
	const images = document.querySelectorAll('img');
	
	// ボタンの状態を更新
	button.disabled = true;
	button.textContent = isMonotone ? 'モノトーン' : '元に戻す';
	
	if (!isMonotone) {
		// モノトーンに変換
		const promises = Array.from(images).map(img => {
			// エラーハンドリング付きで変換
			return convertToMonotone(img).catch(err => {
				console.warn('画像の変換に失敗しました:', img.src, err);
			});
		});
		
		await Promise.all(promises);
		button.style.backgroundColor = '#666';
		isMonotone = true;
	} else {
		// 元に戻す
		images.forEach(restoreOriginal);
		button.style.backgroundColor = '#333';
		isMonotone = false;
	}
	
	button.disabled = false;
}

// ページ読み込み後にボタンを追加
function addButtonToPage() {
	// h1?h5タグを検索
	const headings = document.querySelectorAll('h1, h2, h3, h4, h5');
	
	if (headings.length > 0) {
		// 最初の見出しタグにボタンを追加
		const firstHeading = headings[0];
		const button = createMonotoneButton();
		
		// 見出しの後にボタンを追加
		firstHeading.appendChild(document.createTextNode(' '));
		firstHeading.appendChild(button);
	} else {
		console.warn('h1?h5タグが見つかりません。bodyの先頭にボタンを追加します。');
		// 見出しがない場合はbodyの先頭に追加
		const button = createMonotoneButton();
		document.body.insertBefore(button, document.body.firstChild);
	}
}

// DOMが読み込まれたら実行
if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', addButtonToPage);
} else {
	addButtonToPage();
}
})();
