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