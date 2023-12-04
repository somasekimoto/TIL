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