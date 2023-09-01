### 質問

これでうまくいく理由がわからない
type TupleToUnion<T extends any[]> =  T[number]

/* _____________ テストケース _____________ */
import type { Equal, Expect } from '@type-challenges/utils'

type cases = [
  Expect<Equal<TupleToUnion<[123, '456', true]>, 123 | '456' | true>>,
  Expect<Equal<TupleToUnion<[123]>, 123>>,
]

### 回答

TypeScriptの型システムにおいて、`T[number]`は配列`T`のすべての要素の型を`union`にまとめます。この機能は、特にタプル型に対して非常に便利です。

考え方としては、`T[number]`が`T`という配列の任意の`number`インデックスで取得できる型となります。したがって、その操作は配列`T`の各要素に対応する型を`union`にするという形になるわけです。

例：

- `T = [123, '456', true]` の場合、`T[number]`は `123 | '456' | true`に評価されます。
- `T = [123]` の場合、`T[number]`は `123`に評価されます。

この挙動は配列やタプルに対して非常に柔軟な操作を許してくれます。この特性を活かした`TupleToUnion`型は、タプルの各要素に対応する型を`union`型として取得します。

だから、`T[number]`が"せこい"と感じるかもしれませんが、これは非常に強力な機能です。これを使うことで、配列やタプルに含まれる各要素の型に対して非常に効率的に操作を行うことができます。