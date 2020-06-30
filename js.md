# JSON.parse()
文字列を JSON として解析

```js
const json = '{"result":true, "count":42}';
const obj = JSON.parse(json);

console.log(obj.count);
// 42
console.log(obj.result);
// true
```