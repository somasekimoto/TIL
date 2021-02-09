# htmlファイルダウンロード

https://stackoverflow.com/questions/26219180/download-current-html-file

```html
<a onclick="this.href='data:text/html;charset=UTF-8,'+encodeURIComponent(document.documentElement.outerHTML)" href="#" download="page.html">Download</a>
```


# iframeタグ

https://techacademy.jp/magazine/5839

inline frameタグ

画像や動画の埋め込みを行う時に使う。

```html
<iframe width="560" height="315" src="https://www.youtube.com/embed/-ewm56D9DzY" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
```

# 外部サイトを別タブで表示

https://www.itti.jp/web-design/append-target-nofollow-noopener-external-to-external-links/


```html
<a href="[外部ページのURL]" target="_blank" rel="noopener"></a>
```

## noopener
外部サイトを別タブで表示

## noreferrer
遷移先サイトに遷移元リンクの情報を渡さない


## nofollow
リンク先のウェブページの評価を無効であることを検索サイトに伝える


## checkbox, radio には readonly は使えない