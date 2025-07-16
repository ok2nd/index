// モノトーン画像変換機能（CSS Filter版）
(function() {
	let isMonotone = false;
	const MONOTONE_CLASS = 'monotone-filter';
	// CSSスタイルを追加
	function addFilterStyles() {
		const style = document.createElement('style');
		style.textContent = `
			.${MONOTONE_CLASS} {
				filter: grayscale(100%);
				transition: filter 0.3s ease;
			}
			#monotone-toggle-btn {
				background-color: #333;
				color: white;
				border: none;
				padding: 8px 16px;
				margin: 0 10px;
				border-radius: 4px;
				cursor: pointer;
				font-size: 14px;
				transition: background-color 0.3s;
			}
			#monotone-toggle-btn:hover {
				background-color: #555;
			}
			#monotone-toggle-btn:disabled {
				opacity: 0.6;
				cursor: not-allowed;
			}
			#monotone-toggle-btn.active {
				background-color: #666;
			}
		`;
		document.head.appendChild(style);
	}
	// モノトーンボタンを作成
	function createMonotoneButton() {
		const button = document.createElement('button');
		button.id = 'monotone-toggle-btn';
		button.textContent = 'モノトーン';
		// クリックイベント
		button.addEventListener('click', toggleMonotone);
		return button;
	}
	// モノトーン切り替え
	function toggleMonotone() {
		const button = document.getElementById('monotone-toggle-btn');
		const images = document.querySelectorAll('img');
		if (!isMonotone) {
			// モノトーンに変換
			images.forEach(img => {
				img.classList.add(MONOTONE_CLASS);
			});
			button.textContent = '元に戻す';
			button.classList.add('active');
			isMonotone = true;
		} else {
			// 元に戻す
			images.forEach(img => {
				img.classList.remove(MONOTONE_CLASS);
			});
			button.textContent = 'モノトーン';
			button.classList.remove('active');
			isMonotone = false;
		}
	}
	// 動的に追加された画像にも対応
	function applyToNewImages() {
		if (isMonotone) {
			const images = document.querySelectorAll('img:not(.monotone-filter)');
			images.forEach(img => {
				img.classList.add(MONOTONE_CLASS);
			});
		}
	}
	// MutationObserverで新しい画像の追加を監視
	function observeImageChanges() {
		const observer = new MutationObserver(mutations => {
			mutations.forEach(mutation => {
				if (mutation.type === 'childList') {
					mutation.addedNodes.forEach(node => {
						if (node.nodeType === Node.ELEMENT_NODE) {
							// 追加されたノードが画像の場合
							if (node.tagName === 'IMG') {
								if (isMonotone) {
									node.classList.add(MONOTONE_CLASS);
								}
							}
							// 追加されたノード内の画像をチェック
							const images = node.querySelectorAll && node.querySelectorAll('img');
							if (images && isMonotone) {
								images.forEach(img => {
									img.classList.add(MONOTONE_CLASS);
								});
							}
						}
					});
				}
			});
		});
		observer.observe(document.body, {
			childList: true,
			subtree: true
		});
	}
	// ページ読み込み後にボタンを追加
	function addButtonToPage() {
		// CSSスタイルを追加
		addFilterStyles();
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
		// 動的な画像追加を監視
		observeImageChanges();
	}
	// DOMが読み込まれたら実行
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', addButtonToPage);
	} else {
		addButtonToPage();
	}
})();
