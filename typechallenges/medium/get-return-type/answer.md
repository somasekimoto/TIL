

```ts
type MyReturnType<T> = T extends (...arg: any[])=> infer P ? P : never;
```


### GPTへの質問と回答

- ここでいう、typeof fnやtypeof fn1 って実際の値って何でしょうか？


`typeof` キーワードを使用して、変数、関数、オブジェクトの実際の型を取得することができます。この場合、`fn` と `fn1` は関数なので、`typeof fn` や `typeof fn1` はそれぞれの関数のシグネチャ、つまり関数の型を示します。

具体的に言うと、以下のようになります：

```typescript
const fn = (v: boolean) => v ? 1 : 2;
```
この関数は、`boolean` 型の引数 `v` を取り、戻り値として `1` または `2` を返すので、`fn` の型は `(v: boolean) => 1 | 2` です。そのため、`typeof fn` はこの関数型を指します。

同様に、

```typescript
const fn1 = (v: boolean, w: any) => v ? 1 : 2;
```
この関数は、`boolean` 型の引数 `v` と、どんな型でも良い引数 `w` を取り、戻り値として `1` または `2` を返すので、`fn1` の型は `(v: boolean, w: any) => 1 | 2` です。したがって、`typeof fn1` はこの関数型を指します。

`typeof` を使用することで、変数や関数の現在の型を動的に取得することができ、これは型の操作や比較、条件型などで非常に役立ちます。