

- cargo newを使ってプロジェクトを作成できる

- cargo buildを使ってプロジェクトをビルドできる

- cargo runを使うとプロジェクトのビルドと実行を1ステップで行える

- cargo checkを使うとバイナリを生成せずにプロジェクトをビルドして、エラーがないか確認できる

- Cargoは、ビルドの成果物をコードと同じディレクトリに保存するのではなく、target/debugディレクトリに格納する

- cargo build --release でリリース用に最適化されたコンパイルを行う

- rustc で直接コンパイル、cargo buildでcargo使ったコンパイル