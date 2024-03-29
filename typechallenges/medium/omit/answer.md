
## 質問

```ts
type MyOmit<T, K> = {[P in keyof T as P extends K ? never : P]: T[P]}
```
Omitの問題について、これが回答っぽいんですが、as の使い方がわからないです。
また、extendsのとこも、P extends Kではなく K extends Pなんじゃないかと思ってしまいます。


## 回答

### `as` の使い方

TypeScript での `as` は、マッピング型においてキーの名前を再マッピングするために使用されます。具体的には、マッピング型で `[P in keyof T as ...]` のように使われ、`...` の部分で `P` の新しい名前を指定できます。

この場合、`as P extends K ? never : P` の部分で、`P` が `K` に含まれる（`extends`）場合は、そのキー（プロパティ名）を `never` にしているので、結果としてそのプロパティが除外されます。

### `extends` の使い方

`P extends K` と `K extends P` は異なる意味を持ちます。前者は「`P` が `K` のサブタイプ（または同じ型）であるか？」ということを確認します。後者はその逆で、「`K` が `P` のサブタイプ（または同じ型）であるか？」ということを確認します。

`MyOmit<T, K>` の定義で、目的は「`T` の各キー（プロパティ名）`P` が、除外するべきキー集合 `K` に含まれるか？」を判定することです。そのため、`P extends K` となっています。

もし `K extends P` だとすると、それは「除外するべきキー集合 `K` の各要素が、`T` のキーに含まれるか？」という意味になり、この問題の文脈には合いません。

### 全体の説明

最終的に、この `MyOmit<T, K>` の型は以下のように動きます：

1. `T` のすべてのキー（プロパティ名）についてループを回します（`[P in keyof T ...]` の部分）。
2. 各キー `P` が `K` に含まれるかどうかをチェックします（`P extends K ? ... : ...` の部分）。
3. もし `P` が `K` に含まれるなら、そのキーは `never` になります。それ以外の場合は、`P` そのものになります（`... as P extends K ? never : P` の部分）。
4. この結果として、`K` に含まれるキーが `T` から除外されます。