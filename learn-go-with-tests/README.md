## learn-go-with-tests

https://andmorefine.gitbook.io/learn-go-with-tests/go-fundamentals/hello-world


## テストの種類

- `go test` シンプルなテスト

-  `go test -cover` テストカバレッジ情報を含めてテスト

- `go test -v` verbose

- `go test -bench=.` ベンチマークテスト

```go
package mypackage

import "testing"

func BenchmarkSum(b *testing.B) {
    for i := 0; i < b.N; i++ {
        Sum(1, 2)
    }
}
``` 

-  `reflect.DeepEqual` は、2つの引数が深い意味で等しいかどうかを判断します。これは、配列やスライスなどの複雑なデータ構造を比較するのに便利です。例えば、`reflect.DeepEqual([]int{1, 2}, []int{1, 2})`は`true`を返します。ですが、型の安全性は下がります。

