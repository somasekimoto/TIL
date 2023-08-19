# docker file のベストプラクティス

https://sysdig.jp/blog/dockerfile-best-practices/

# container の build ができない時 （No space left on device）

```
#5 135.2 ERROR: Failed to create usr/lib/libsupc++.a: No space left on device
```

ホストのディスク容量の不足

https://qiita.com/ponsuke0531/items/edf2eee638202aa7f61f


```
df -h 
```

でどこで容量食っているのか確かめ、削除する。
