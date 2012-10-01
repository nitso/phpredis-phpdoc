<?php
/**
 * @author: ooostin@gmail.com
 * @created: 02.10.12 0:42
 *
 * @method string echo(string $message) Sends a string to Redis, which replies with the same string
 */
class Redis {
    const OPT_SERIALIZER = 1;
    const SERIALIZER_NONE = 0;
    const SERIALIZER_PHP = 1;
    const SERIALIZER_IGBINARY = 2;

    const OPT_PREFIX = 2;

    const MULTI = 1;

    const BEFORE = 'before';
    const AFTER = 'after';

    const REDIS_STRING = 1;
    const REDIS_SET = 2;
    const REDIS_LIST = 3;
    const REDIS_ZSET = 4;
    const REDIS_HASH = 5;
    const REDIS_NOT_FOUND = 0;

    /**
     * Connects to a Redis instance.
     * <pre>
     * $redis->connect('127.0.0.1', 6379);
     * $redis->connect('127.0.0.1'); // port 6379 by default
     * $redis->connect('127.0.0.1', 6379, 2.5); // 2.5 sec timeout.
     * $redis->connect('/tmp/redis.sock'); // unix domain socket.
     * </pre>
     *
     * @param string $host can be a host, or the path to a unix domain socket
     * @param string $port optional
     * @param float $timeout value in seconds (optional, default is 0 meaning unlimited)
     * @return bool
     */
    public function connect($host, $port, $timeout) {}
    /**
     * Connects to a Redis instance.
     * @see connect()
     */
    public function open($host, $port, $timeout) {}

    /**
     * Connects to a Redis instance or reuse a connection already established with `pconnect`/`popen`.
     * The connection will not be closed on `close` or end of request until the php process ends.
     * So be patient on to many open FD's (specially on redis server side) when using persistent
     * connections on many servers connecting to one redis server.
     *
     * Also more than one persistent connection can be made identified by either host + port + timeout
     * or host + persistent_id or unix socket + timeout.
     * This feature is not available in threaded versions. `pconnect` and `popen` then working like their non
     * persistent equivalents.
     *
     * @param string $host can be a host, or the path to a unix domain socket
     * @param int $port optional
     * @param float $timeout value in seconds (optional, default is 0 meaning unlimited)
     * @param string $persistentId identity for the requested persistent connection
     * @return bool
     */
    public function pconnect($host, $port, $timeout, $persistentId) {}

    /**
     * Connects to a Redis instance or reuse a connection already established with `pconnect`/`popen`.
     *
     * @see pconnect()
     */
    public function popen($host, $port, $timeout, $persistentId) {}

    /**
     * Disconnects from the Redis instance, except when `pconnect` is used.
     */
    public function close() {}

    /**
     * Set client option.
     * <pre>
     * $redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_NONE);	// don't serialize data
     * $redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);	// use built-in serialize/unserialize
     * $redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_IGBINARY);	// use igBinary serialize/unserialize
     * $redis->setOption(Redis::OPT_PREFIX, 'myAppName:');	// use custom prefix on all keys
     * </pre>
     *
     * @param $name
     * @param $value
     * @return bool
     */
    public function setOption($name, $value) {}

    /**
     * Get client option
     * <pre>
     * $redis->getOption(Redis::OPT_SERIALIZER);	// return Redis::SERIALIZER_NONE, Redis::SERIALIZER_PHP, or Redis::SERIALIZER_IGBINARY.
     * </pre>
     *
     * @param $name
     */
    public function getOption($name) {}

    /**
     * Check the current connection status
     * @return string `+PONG` on success.
     * @throws RedisException on connectivity error, as described above.
     */
    public function ping() {}

    /**
     * Get the value related to the specified key
     *
     * @param $key
     * @return string|bool If key didn't exist, `FALSE` is returned. Otherwise, the value related to this key is returned.
     */
    public function get($key) {}

    /**
     * Set the string value in argument as value of the key.
     *
     * @param string $key
     * @param string $value
     * @param float $timeout optional. Calling setex() is preferred if you want a timeout.
     * @see setex()
     */
    public function set($key, $value, $timeout = null) {}

    /**
     * Set the string value in argument as value of the key, with a time to live
     * <pre>
     * $redis->setex('key', 3600, 'value'); // sets key → value, with 1h TTL.
     * </pre>
     *
     * @param string $key
     * @param int $ttl time in seconds
     * @param string $value
     * @return bool
     * @see psetex()
     */
    public function setex($key, $ttl, $value) {}

    /**
     * Set the string value in argument as value of the key, with a time to live
     * <pre>
     * $redis->psetex('key', 100, 'value'); // sets key → value, with 0.1 sec TTL.
     * </pre>
     *
     * @param string $key
     * @param int $ttl time in milliseconds
     * @param string $value
     * @return bool
     * @see setex()
     */
    public function psetex($key, $ttl, $value) {}

    /**
     * Set the string value in argument as value of the key if the key doesn't already exist in the database.
     * <pre>
     * $redis->setnx('key', 'value'); // return TRUE
     * $redis->setnx('key', 'value'); // return FALSE
     * </pre>
     *
     * @param string $key
     * @param string $value
     * @return bool
     */
    public function setnx($key, $value) {}

    /**
     * Remove specified keys.
     * <pre>
     * $redis->delete('key1', 'key2'); // return 2
     * $redis->delete(array('key3', 'key4')); // return 2
     * </pre>
     *
     * @param string|array $key An array of keys, or number of parameters, each a key
     * @return int Number of keys deleted.
     */
    public function del($key) {}

    /**
     * @see del()
     * @param $key
     */
    public function delete($key) {}

    /**
     * Enter transactional mode.
     * <pre>
     * $ret = $redis->multi()
     * ->set('key1', 'val1')
     * ->get('key1')
     * ->set('key2', 'val2')
     * ->get('key2')
     * ->exec();
     * // $ret == array(
     * //  0 => TRUE,
     * //  1 => 'val1',
     * //  2 => TRUE,
     * //  3 => 'val2');
     * </pre>
     *
     * @param int $mode Redis::MULTI or Redis::PIPELINE
     * A Redis::MULTI block of commands runs as a single transaction;
     * a Redis::PIPELINE block is simply transmitted faster to the server, but without any guarantee of atomicity.
     */
    public function multi($mode = Redis::MULTI) {}

    /**
     * Execute a transaction
     * @see multi()
     */
    public function exec() {}

    /**
     * Discard a transaction
     * @see multi()
     */
    public function discard() {}

    /**
     * Watches a key for modifications by another client.
     * If the key is modified between watch() and exec(), the MULTI/EXEC transaction will fail (return FALSE).
     * <pre>
     * $redis->watch('x');
     * // long code here during the execution of which other clients could well modify `x`
     * $ret = $redis->multi()
     * ->incr('x')
     * ->exec();
     * // $ret = FALSE if x has been modified between the call to WATCH and the call to EXEC.
     * </pre>
     *
     * @param $keys
     */
    public function watch($keys) {}

    /**
     * Cancels all the watching of all keys by this client.
     * @see watch()
     */
    public function unwatch() {}

    /**
     * Subscribe to channels. Warning: this function will probably change in the future.
     * <pre>
     * function f($redis, $chan, $msg) {
     *   switch($chan) {
     *     case 'chan-1':
     *     ...
     *     break;
     *     case 'chan-2':
     *     ...
     *     break;
     *     case 'chan-2':
     *     ...
     *     break;
     *   }
     * }
     * $redis->subscribe(array('chan-1', 'chan-2', 'chan-3'), 'f'); // subscribe to 3 chans
     * </pre>
     *
     * @param array $channels an array of channels to subscribe to
     * @param string|array $callback either a string or an array($instance, 'method_name').
     * The callback function receives 3 parameters: the redis instance, the channel name, and the message.
     */
    public function subscribe($channels, $callback) {}

    /**
     * Subscribe to channels by pattern
     * <pre>
     * function psubscribe($redis, $pattern, $chan, $msg) {
     *   echo "Pattern: $pattern\n";
     *   echo "Channel: $chan\n";
     *   echo "Payload: $msg\n";
     * }
     * </pre>
     *
     * @param array $patterns An array of patterns to match
     * @param string|array $callback Either a string or an array with an object and method.
     * The callback will get four arguments ($redis, $pattern, $channel, $message)
     */
    public function psubscribe($patterns, $callback) {}

    /**
     * Publish messages to channels. Warning: this function will probably change in the future.
     *
     * @param string $channel a channel to publish to
     * @param string  $message
     */
    public function publish($channel, $message) {}

    /**
     * Verify if the specified key exists.
     * @param $key
     * @return bool
     */
    public function exists($key) {}

    /**
     * Increment the number stored at key by one.
     *
     * @param string $key
     */
    public function incr($key) {}

    /**
     * Increment the number stored at key by the integer value of the increment.
     *
     * @param string $key
     * @param int $value
     * @return int new value
     */
    public function incrBy($key, $value) {}

    /**
     * Increment the key with floating point precision.
     *
     * @param string $key
     * @param float $value
     * @return float new value
     */
    public function incrByFloat($key, $value) {}

    /**
     * Decrement the number stored at key by one.
     *
     * @param string $key
     */
    public function decr($key) {}

    /**
     * Decrement the number stored at key by the integer value of the decrement.
     *
     * @param string $key
     * @param int $value
     */
    public function decrBy($key, $value) {}

    /**
     * Get the values of all the specified keys.
     * If one or more keys dont exist, the array will contain `FALSE` at the position of the key.
     *
     * @param array $keys Array containing the list of the keys
     * @return array Array containing the values related to keys in argument
     */
    public function mget($keys) {}

    /**
     * Adds the string value to the head (left) of the list.
     * Creates the list if the key didn't exist. If the key exists and is not a list, `FALSE` is returned.
     *
     * @param string $key
     * @param string $value value to push in key
     *
     * @return int|bool The new length of the list in case of success, `FALSE` in case of Failure.
     */
    public function lpush($key, $value) {}

    /**
     * Adds the string value to the tail (right) of the list.
     * Creates the list if the key didn't exist. If the key exists and is not a list, `FALSE` is returned.
     *
     * @param string $key
     * @param string $value value to push in key
     * @return int|bool The new length of the list in case of success, `FALSE` in case of Failure.
     */
    public function rpush($key, $value) {}

    /**
     * Adds the string value to the head (left) of the list if the list exists.
     *
     * @param $key
     * @param $value
     * @return int|bool The new length of the list in case of success, FALSE in case of Failure.
     */
    public function lpushx($key, $value) {}

    /**
     * Adds the string value to the tail (right) of the list if the ist exists. FALSE in case of Failure.
     *
     * @param $key
     * @param $value
     * @return int|bool The new length of the list in case of success, FALSE in case of Failure.
     */
    public function rpushx($key, $value) {}

    /**
     * Return and remove the first element of the list.
     *
     * @param string $key
     * @return string|bool STRING if command executed successfully, FALSE in case of failure (empty list)
     */
    public function lpop($key) {}

    /**
     * Returns and removes the last element of the list.
     *
     * @param string $key
     * @return bool|string STRING if command executed successfully, FALSE in case of failure (empty list)
     */
    public function rpop($key) {}

    /**
     * Is a blocking lPop(rPop) primitive.
     * If at least one of the lists contains at least one element, the element will be popped from the head of the list
     * and returned to the caller. Il all the list identified by the keys passed in arguments are empty,
     * blPop will block during the specified timeout until an element is pushed to one of those lists.
     * This element will be popped.
     *
     * @param string|array $key one or more keys
     * @param int $timeout
     * @return array array('listName', 'element')
     */
    public function blPop($key, $timeout) {}

    /**
     * @see blPop()
     * @param string|array $key
     * @param int $timeout
     * @return array
     */
    public function brPop($key, $timeout) {}

    /**
     * Returns the size of a list identified by Key.
     * If the list didn't exist or is empty, the command returns 0.
     * If the data type identified by Key is not a list, the command return `FALSE`.
     *
     * @param string $key
     * @return int|bool
     */
    public function lSize($key) {}

    /**
     * Return the specified element of the list stored at the specified key.
     * 0 the first element, 1 the second ...
     * -1 the last element, -2 the penultimate ...
     *
     * @param string $key
     * @param int $index
     * @return string|bool `FALSE` in case of a bad index or a key that doesn't point to a list.
     */
    public function lIndex($key, $index) {}

    /**
     * Return the specified element of the list stored at the specified key.
     *
     * @see lIndex()
     * @param string $key
     * @param int $index
     * @return string|bool
     */
    public function lGet($key, $index) {}

    /**
     * Set the list at index with the new value.
     *
     * @param string $key
     * @param int $index
     * @param string $value
     * @return bool TRUE if the new value is setted. FALSE if the index is out of range, or data type identified by key is not a list.
     */
    public function lSet($key, $index, $value) {}

    /**
     * Returns the specified elements of the list stored at the specified key in the range [start, end].
     * Start and stop are interpretated as indices:
     * 0 the first element, 1 the second ...
     * -1 the last element, -2 the penultimate ...
     *
     * @param string $key
     * @param int $start
     * @param int $end
     * @return array containing the values in specified range.
     */
    public function lRange($key, $start, $end) {}

    /**
     * Returns the specified elements of the list stored at the specified key in the range [start, end].
     *
     * @param string $key
     * @param int $start
     * @param int $end
     * @return array containing the values in specified range.
     * @see lRange()
     */
    public function lGetRange($key, $start, $end) {}

    /**
     * Trims an existing list so that it will contain only a specified range of elements.
     *
     * @param string $key
     * @param int $start
     * @param int $stop
     * @return array|bool return `FALSE` if the key identify a non-list value.
     */
    public function lTrim($key, $start, $stop) {}

    /**
     * Trims an existing list so that it will contain only a specified range of elements.
     *
     * @param string $key
     * @param int $start
     * @param int $stop
     * @return array|bool return `FALSE` if the key identify a non-list value.
     * @see lTrim()
     */
    public function listTrim($key, $start, $stop) {}

    /**
     * Removes the first `count` occurences of the value element from the list.
     * If count is zero, all the matching elements are removed.
     * If count is negative, elements are removed from tail to head.
     * Note: The argument order is not the same as in the Redis documentation. This difference is kept for compatibility reasons.
     *
     * @param string $key
     * @param string $value
     * @param int $count
     * @return bool|int the number of elements to remove, `FALSE` if the value identified by key is not a list.
     */
    public function lRem($key, $value, $count) {}

    /**
     * Removes the first `count` occurences of the value element from the list.
     *
     * @param string $key
     * @param string $value
     * @param int $count
     * @return bool|int the number of elements to remove, `FALSE` if the value identified by key is not a list.
     * @see lRem()
     */
    public function lRemove($key, $value, $count) {}

    /**
     * Insert value in the list before or after the pivot value. the parameter options specify the position of the insert (before or after).
     * If the list didn't exists, or the pivot didn't exists, the value is not inserted.
     *
     * @param $key
     * @param $position Redis::BEFORE | Redis::AFTER
     * @param $pivot
     * @param $value
     * @return int The number of the elements in the list, -1 if the pivot didn't exists.
     */
    public function lInsert($key, $position, $pivot, $value) {}

    /**
     * Adds a value to the set value stored at key.
     *
     * @param $key
     * @param $value
     * @return int|bool the number of elements added to the set, FALSE if this value is already in the set
     */
    public function sAdd($key, $value) {}

    /**
     * Removes the specified member from the set value stored at key.
     *
     * @param $key
     * @param $member
     * @return int The number of elements removed from the set.
     */
    public function sRem($key, $member) {}

    /**
     * Removes the specified member from the set value stored at key.
     *
     * @param $key
     * @param $member
     * @return int The number of elements removed from the set.
     * @see sRem
     */
    public function sRemove($key, $member) {}

    /**
     * Moves the specified member from the set at srcKey to the set at dstKey.
     *
     * @param $srcKey
     * @param $dstKey
     * @param $member
     * @return bool If the operation is successful, return `TRUE`.
     * If the srcKey and/or dstKey didn't exist, and/or the member didn't exist in srcKey, `FALSE` is returned.
     */
    public function sMove($srcKey, $dstKey, $member) {}

    /**
     * Checks if `value` is a member of the set stored at the key `key`.
     *
     * @param $key
     * @param $value
     * @return bool `TRUE` if `value` is a member of the set at key `key`, `FALSE` otherwise.
     */
    public function sIsMember($key, $value) {}

    /**
     * Checks if `value` is a member of the set stored at the key `key`.
     *
     * @param $key
     * @param $value
     * @return bool `TRUE` if `value` is a member of the set at key `key`, `FALSE` otherwise.
     * @see sIsMember()
     */
    public function sContains($key, $value) {}

    /**
     * Returns the cardinality of the set identified by key.
     * @param $key
     * @return int the cardinality of the set identified by key, 0 if the set doesn't exist.
     */
    public function sCard($key) {}

    /**
     * Returns the cardinality of the set identified by key.
     * @param $key
     * @return int the cardinality of the set identified by key, 0 if the set doesn't exist.
     * @see sCard()
     */
    public function sSize($key) {}

    /**
     * Removes and returns a random element from the set value at Key.
     *
     * @param $key
     * @return string|bool "popped" value, `FALSE` if set identified by key is empty or doesn't exist.
     */
    public function sPop($key) {}

    /**
     * Returns a random element from the set value at Key, without removing it.
     *
     * @param $key
     * @return string|bool value from the set `FALSE` if set identified by key is empty or doesn't exist.
     */
    public function sRandMember($key) {}

    /**
     * Returns the members of a set resulting from the intersection of all the sets held at the specified keys.
     * If just a single key is specified, then this command produces the members of this set.
     *
     * @param string $key1 keys identifying the different sets on which we will apply the intersection.
     * @param string $key2
     * @param string $keyn
     * @return array|bool contain the result of the intersection between those keys.
     * If the intersection beteen the different sets is empty, the return value will be empty array.
     * If one of the keys is missing, `FALSE` is returned.
     */
    public function sInter($key1, $key2 = null, $keyn = null) {}

    /**
     * Performs a sInter command and stores the result in a new set.
     *
     * @param string $key dstkey, the key to store the diff into.
     * @param string $keys1
     * @param string $keysn are intersected as in sInter
     * @return int|bool The cardinality of the resulting set, or `FALSE` in case of a missing key.
     * @see sInter()
     */
    public function sInterScope($key, $keys1 = null, $keysn = null) {}

    /**
     * Performs the union between N sets and returns it.
     *
     * @param string $key1 Any number of keys corresponding to sets in redis.
     * @param string $key2
     * @param string $keyn
     * @return array The union of all these sets.
     */
    public function sUnion($key1, $key2 = null, $keyn = null) {}

    /**
     * Performs the same action as sUnion, but stores the result in the first key
     *
     * @param string $key dstkey, the key to store the diff into.
     * @param string $keys1
     * @param string $keysn
     * @see sUnion()
     * @return int|bool The cardinality of the resulting set, or `FALSE` in case of a missing key.
     */
    public function sUnionStore($key, $keys1 = null, $keysn = null) {}

    /**
     * Performs the difference between N sets and returns it.
     *
     * @param string $key1 Any number of keys corresponding to sets in redis.
     * @param string $key2
     * @param string $keyn
     * @return array The difference of the first set will all the others.
     */
    public function sDiff($key1, $key2 = null, $keyn = null) {}

    /**
     * Performs the same action as sDiff, but stores the result in the first key
     *
     * @param string $key dstkey, the key to store the diff into.
     * @param string $keys1 Any number of keys corresponding to sets in redis
     * @param string $keysn
     * @see sDiff()
     * @return int|bool The cardinality of the resulting set, or `FALSE` in case of a missing key.
     */
    public function sDiffStore($key, $keys1, $keysn) {}

    /**
     * Returns the contents of a set.
     * The order is random and corresponds to redis' own internal representation of the set structure.
     *
     * @param $key
     * @return array
     */
    public function sMembers($key) {}

    /**
     * Returns the contents of a set.
     *
     * @param $key
     * @return array
     * @see sMembers()
     */
    public function sGetMembers($key) {}

    /**
     * Sets a value and returns the previous entry at that key.
     *
     * @param $key
     * @param $value
     * @return string A string, the previous value located at this key.
     */
    public function getSet($key, $value) {}

    /**
     * Returns a random key.
     *
     * @return string an existing key in redis.
     */
    public function randomKey() {}

    /**
     * Switches to a given database.
     *
     * @param int $dbIndex
     * @return bool
     */
    public function select($dbIndex) {}

    /**
     * Moves a key to a different database.
     *
     * @param $key
     * @param $dbIndex
     */
    public function move($key, $dbIndex) {}

    /**
     * Renames a key.
     *
     * @param $srcKey
     * @param $dstKey
     * @return bool
     */
    public function rename($srcKey, $dstKey) {}

    /**
     * Renames a key.
     *
     * @param $srcKey
     * @param $dstKey
     * @return bool
     * @see rename()
     */
    public function renameKey($srcKey, $dstKey) {}

    /**
     * Same as rename, but will not replace a key if the destination already exists. This is the same behaviour as setNx.
     *
     * @param $srcKey
     * @param $dstKey
     * @return bool
     * @see rename()
     */
    public function renameNx($srcKey, $dstKey) {}

    /**
     * Sets an expiration date (a timeout) on an item in seconds
     *
     * @param $key
     * @param $ttl
     * @return bool
     */
    public function setTimeOut($key, $ttl) {}

    /**
     * Sets an expiration date (a timeout) on an item in seconds
     *
     * @param $key
     * @param $ttl
     * @see setTimeOut()
     * @return bool
     */
    public function expire($key, $ttl) {}

    /**
     * Sets an expiration date (a timeout) on an item in milliseconds
     *
     * @param $key
     * @param $ttl
     * @return bool
     */
    public function pexpire($key, $ttl) {}

    /**
     * Sets an expiration date (a timestamp) on an item in seconds
     * @param $key
     * @param int $timestamp Unix timestamp.
     * @return bool
     */
    public function expireAt($key, $timestamp) {}

    /**
     * Sets an expiration date (a timestamp) on an item in milliseconds
     * @param $key
     * @param $timestamp
     * @return bool
     */
    public function pexpireAt($key, $timestamp) {}

    /**
     * Returns the keys that match a certain pattern.
     * @param string $pattern using '*' as a wildcard.
     * @return array The keys that match a certain pattern.
     */
    public function keys($pattern) {}

    /**
     * Returns the keys that match a certain pattern.
     * @param $pattern
     * @see keys()
     * @return array
     */
    public function getKeys($pattern) {}

    /**
     * Returns the current database's size.
     *
     * @return int DB size, in number of keys.
     */
    public function dbSize() {}

    /**
     * Authenticate the connection using a password.
     * Warning: The password is sent in plain-text over the network.
     *
     * @param $password
     * @return bool
     */
    public function auth($password) {}

    /**
     * Starts the background rewrite of AOF (Append-Only File)
     *
     * @return bool
     */
    public function bgrewriteaof() {}

    /**
     * Changes the slave status
     *
     * @param string $host
     * @param int $port
     * @return bool
     */
    public function slaveof($host = null, $port = null) {}

    /**
     * Describes the object pointed to by a key.
     *
     *
     * @param string $retrieve  'encoding', 'refcount' or 'idletime'
     * @param string $key
     * @return string|bool
     */
    public function object($retrieve, $key) {}

    /**
     * Performs a synchronous save.
     * If a save is already running, this command will fail and return `FALSE`.
     *
     * @return bool
     */
    public function save() {}

    /**
     * Performs a background save.
     * If a save is already running, this command will fail and return `FALSE`.
     *
     * @return bool
     */
    public function bgsave() {}

    /**
     * Returns the timestamp of the last disk save.
     *
     * @return int timestamp
     */
    public function lastSave() {}

    /**
     * Returns the type of data pointed by a given key.
     * Depending on the type of the data pointed by the key, this method will return the following value:
     * string: Redis::REDIS_STRING
     * set: Redis::REDIS_SET
     * list: Redis::REDIS_LIST
     * zset: Redis::REDIS_ZSET
     * hash: Redis::REDIS_HASH
     * other: Redis::REDIS_NOT_FOUND
     *
     * @param $key
     * @return int
     */
    public function type($key) {}

    /**
     * Append specified string to the string stored in specified key.
     *
     * @param $key
     * @param $value
     * @return int Size of the value after the append
     */
    public function append($key, $value) {}
}
?>


## getRange (substr also supported but deprecated in redis)
##### *Description*
Return a substring of a larger string

##### *Parameters*
*key*
*start*
*end*

##### *Return value*
*STRING*: the substring

##### *Example*
<pre>
$redis->set('key', 'string value');
$redis->getRange('key', 0, 5); /* 'string' */
$redis->getRange('key', -5, -1); /* 'value' */
</pre>

## setRange
##### *Description*
Changes a substring of a larger string.

##### *Parameters*
*key*
*offset*
*value*

##### *Return value*
*STRING*: the length of the string after it was modified.

##### *Example*
<pre>
$redis->set('key', 'Hello world');
$redis->setRange('key', 6, "redis"); /* returns 11 */
$redis->get('key'); /* "Hello redis" */
</pre>

## strlen
##### *Description*
Get the length of a string value.

##### *Parameters*
*key*

##### *Return value*
*INTEGER*

##### *Example*
<pre>
$redis->set('key', 'value');
$redis->strlen('key'); /* 5 */
</pre>

## getBit
##### *Description*
Return a single bit out of a larger string

##### *Parameters*
*key*
*offset*

##### *Return value*
*LONG*: the bit value (0 or 1)

##### *Example*
<pre>
$redis->set('key', "\x7f"); // this is 0111 1111
$redis->getBit('key', 0); /* 0 */
$redis->getBit('key', 1); /* 1 */
</pre>

## setBit
##### *Description*
Changes a single bit of a string.

##### *Parameters*
*key*
*offset*
*value*: bool or int (1 or 0)

##### *Return value*
*LONG*: 0 or 1, the value of the bit before it was set.

##### *Example*
<pre>
$redis->set('key', "*");	// ord("*") = 42 = 0x2f = "0010 1010"
$redis->setBit('key', 5, 1); /* returns 0 */
$redis->setBit('key', 7, 1); /* returns 0 */
$redis->get('key'); /* chr(0x2f) = "/" = b("0010 1111") */
</pre>

## bitop
##### *Description*
Bitwise operation on multiple keys.

##### *Parameters*
*operation*: either "AND", "OR", "NOT", "XOR"
*ret_key*: return key
*key1*
*key2...*

##### *Return value*
*LONG*: The size of the string stored in the destination key.

## bitcount
##### *Description*
Count bits in a string.

##### *Parameters*
*key*

##### *Return value*
*LONG*: The number of bits set to 1 in the value behind the input key.

## flushDB

##### *Description*
Removes all entries from the current database.

##### *Parameters*
None.

##### *Return value*
*BOOL*: Always `TRUE`.

##### *Example*
<pre>
$redis->flushDB();
</pre>


## flushAll
##### *Description*
Removes all entries from all databases.

##### *Parameters*
None.

##### *Return value*
*BOOL*: Always `TRUE`.

##### *Example*
<pre>
$redis->flushAll();
</pre>

## sort
##### *Description*
##### *Parameters*
*Key*: key
*Options*: array(key => value, ...) - optional, with the following keys and values:
<pre>
'by' => 'some_pattern_*',
'limit' => array(0, 1),
'get' => 'some_other_pattern_*' or an array of patterns,
'sort' => 'asc' or 'desc',
'alpha' => TRUE,
'store' => 'external-key'
</pre>
##### *Return value*
An array of values, or a number corresponding to the number of elements stored if that was used.

##### *Example*
<pre>
$redis->delete('s');
$redis->sadd('s', 5);
$redis->sadd('s', 4);
$redis->sadd('s', 2);
$redis->sadd('s', 1);
$redis->sadd('s', 3);

var_dump($redis->sort('s')); // 1,2,3,4,5
var_dump($redis->sort('s', array('sort' => 'desc'))); // 5,4,3,2,1
var_dump($redis->sort('s', array('sort' => 'desc', 'store' => 'out'))); // (int)5
</pre>


## info
##### *Description*
Returns an associative array from REDIS that provides information about the server.  Passing
no arguments to INFO will call the standard REDIS INFO command, which returns information such
as the following:

* redis_version
* arch_bits
* uptime_in_seconds
* uptime_in_days
* connected_clients
* connected_slaves
* used_memory
* changes_since_last_save
* bgsave_in_progress
* last_save_time
* total_connections_received
* total_commands_processed
* role

You can pass a variety of options to INFO (per the Redis documentation), which will modify what is
returned.

##### *Parameters*
*option*: The option to provide redis (e.g. "COMMANDSTATS", "CPU")

##### *Example*
<pre>
$redis->info(); /* standard redis INFO command */
$redis->info("COMMANDSTATS"); /* Information on the commands that have been run (>=2.6 only)
$redis->info("CPU"); /* just CPU information from Redis INFO */
</pre>

## resetStat
##### *Description*
Resets the statistics reported by Redis using the INFO command (`info()` function).

These are the counters that are reset:

* Keyspace hits
* Keyspace misses
* Number of commands processed
* Number of connections received
* Number of expired keys


##### *Parameters*
None.

##### *Return value*
*BOOL*: `TRUE` in case of success, `FALSE` in case of failure.

##### *Example*
<pre>
$redis->resetStat();
</pre>

## ttl, pttl
##### *Description*
Returns the time to live left for a given key, in seconds. If the key doesn't exist, `FALSE` is returned. pttl returns a time in milliseconds.

##### *Parameters*
*Key*: key

##### *Return value*
Long, the time left to live in seconds.

##### *Example*
<pre>
$redis->ttl('key');
</pre>

## persist
##### *Description*
Remove the expiration timer from a key.

##### *Parameters*
*Key*: key

##### *Return value*
*BOOL*: `TRUE` if a timeout was removed, `FALSE` if the key didn’t exist or didn’t have an expiration timer.

##### *Example*
<pre>
$redis->persist('key');
</pre>

## mset, msetnx
##### *Description*
Sets multiple key-value pairs in one atomic command. MSETNX only returns TRUE if all the keys were set (see SETNX).

##### *Parameters*
*Pairs*: array(key => value, ...)

##### *Return value*
*Bool* `TRUE` in case of success, `FALSE` in case of failure.

##### *Example*
<pre>

$redis->mset(array('key0' => 'value0', 'key1' => 'value1'));
var_dump($redis->get('key0'));
var_dump($redis->get('key1'));

</pre>
Output:
<pre>
string(6) "value0"
string(6) "value1"
</pre>


## rpoplpush (redis >= 1.1)
##### *Description*
Pops a value from the tail of a list, and pushes it to the front of another list. Also return this value.

##### *Parameters*
*Key*: srckey
*Key*: dstkey

##### *Return value*
*STRING* The element that was moved in case of success, `FALSE` in case of failure.

##### *Example*
<pre>
$redis->delete('x', 'y');

$redis->lPush('x', 'abc');
$redis->lPush('x', 'def');
$redis->lPush('y', '123');
$redis->lPush('y', '456');

// move the last of x to the front of y.
var_dump($redis->rpoplpush('x', 'y'));
var_dump($redis->lRange('x', 0, -1));
var_dump($redis->lRange('y', 0, -1));

</pre>
Output:
<pre>
string(3) "abc"
array(1) {
[0]=>
string(3) "def"
}
array(3) {
[0]=>
string(3) "abc"
[1]=>
string(3) "456"
[2]=>
string(3) "123"
}
</pre>

## brpoplpush
##### *Description*
A blocking version of `rpoplpush`, with an integral timeout in the third parameter.

##### *Parameters*
*Key*: srckey
*Key*: dstkey
*Long*: timeout

##### *Return value*
*STRING* The element that was moved in case of success, `FALSE` in case of timeout.


## zAdd
##### *Description*
Adds the specified member with a given score to the sorted set stored at key.
##### *Parameters*
*key*
*score* : double
*value*: string

##### *Return value*
*Long* 1 if the element is added. 0 otherwise.
##### *Example*
<pre>
$redis->zAdd('key', 1, 'val1');
$redis->zAdd('key', 0, 'val0');
$redis->zAdd('key', 5, 'val5');
$redis->zRange('key', 0, -1); // array(val0, val1, val5)
</pre>

## zRange
##### *Description*
Returns a range of elements from the ordered set stored at the specified key, with values in the range [start, end]. start and stop are interpreted as zero-based indices:
0 the first element, 1 the second ...
-1 the last element, -2 the penultimate ...
##### *Parameters*
*key*
*start*: long
*end*: long
*withscores*: bool = false

##### *Return value*
*Array* containing the values in specified range.
##### *Example*
<pre>
$redis->zAdd('key1', 0, 'val0');
$redis->zAdd('key1', 2, 'val2');
$redis->zAdd('key1', 10, 'val10');
$redis->zRange('key1', 0, -1); /* array('val0', 'val2', 'val10') */

// with scores
$redis->zRange('key1', 0, -1, true); /* array('val0' => 0, 'val2' => 2, 'val10' => 10) */
</pre>

## zDelete, zRem
##### *Description*
Deletes a specified member from the ordered set.
##### *Parameters*
*key*
*member*

##### *Return value*
*LONG* 1 on success, 0 on failure.
##### *Example*
<pre>
$redis->zAdd('key', 0, 'val0');
$redis->zAdd('key', 2, 'val2');
$redis->zAdd('key', 10, 'val10');
$redis->zDelete('key', 'val2');
$redis->zRange('key', 0, -1); /* array('val0', 'val10') */
</pre>

## zRevRange
##### *Description*
Returns the elements of the sorted set stored at the specified key in the range [start, end] in reverse order. start and stop are interpretated as zero-based indices:
0 the first element, 1 the second ...
-1 the last element, -2 the penultimate ...

##### *Parameters*
*key*
*start*: long
*end*: long
*withscores*: bool = false

##### *Return value*
*Array* containing the values in specified range.
##### *Example*
<pre>
$redis->zAdd('key', 0, 'val0');
$redis->zAdd('key', 2, 'val2');
$redis->zAdd('key', 10, 'val10');
$redis->zRevRange('key', 0, -1); /* array('val10', 'val2', 'val0') */

// with scores
$redis->zRevRange('key', 0, -1, true); /* array('val10' => 10, 'val2' => 2, 'val0' => 0) */
</pre>

## zRangeByScore, zRevRangeByScore
##### *Description*
Returns the elements of the sorted set stored at the specified key which have scores in the range [start,end]. Adding a parenthesis before `start` or `end` excludes it from the range. +inf and -inf are also valid limits. zRevRangeByScore returns the same items in reverse order, when the `start` and `end` parameters are swapped.
##### *Parameters*
*key*
*start*: string
*end*: string
*options*: array

Two options are available: `withscores => TRUE`, and `limit => array($offset, $count)`
##### *Return value*
*Array* containing the values in specified range.
##### *Example*
<pre>
$redis->zAdd('key', 0, 'val0');
$redis->zAdd('key', 2, 'val2');
$redis->zAdd('key', 10, 'val10');
$redis->zRangeByScore('key', 0, 3); /* array('val0', 'val2') */
$redis->zRangeByScore('key', 0, 3, array('withscores' => TRUE); /* array('val0' => 0, 'val2' => 2) */
$redis->zRangeByScore('key', 0, 3, array('limit' => array(1, 1)); /* array('val2' => 2) */
$redis->zRangeByScore('key', 0, 3, array('limit' => array(1, 1)); /* array('val2') */
$redis->zRangeByScore('key', 0, 3, array('withscores' => TRUE, 'limit' => array(1, 1)); /* array('val2' => 2) */
</pre>

## zCount
##### *Description*
Returns the *number* of elements of the sorted set stored at the specified key which have scores in the range [start,end]. Adding a parenthesis before `start` or `end` excludes it from the range. +inf and -inf are also valid limits.
##### *Parameters*
*key*
*start*: string
*end*: string

##### *Return value*
*LONG* the size of a corresponding zRangeByScore.
##### *Example*
<pre>
$redis->zAdd('key', 0, 'val0');
$redis->zAdd('key', 2, 'val2');
$redis->zAdd('key', 10, 'val10');
$redis->zCount('key', 0, 3); /* 2, corresponding to array('val0', 'val2') */
</pre>

## zRemRangeByScore, zDeleteRangeByScore
##### *Description*
Deletes the elements of the sorted set stored at the specified key which have scores in the range [start,end].
##### *Parameters*
*key*
*start*: double or "+inf" or "-inf" string
*end*: double or "+inf" or "-inf" string

##### *Return value*
*LONG* The number of values deleted from the sorted set
##### *Example*
<pre>
$redis->zAdd('key', 0, 'val0');
$redis->zAdd('key', 2, 'val2');
$redis->zAdd('key', 10, 'val10');
$redis->zRemRangeByScore('key', 0, 3); /* 2 */
</pre>

## zRemRangeByRank, zDeleteRangeByRank
##### *Description*
Deletes the elements of the sorted set stored at the specified key which have rank in the range [start,end].
##### *Parameters*
*key*
*start*: LONG
*end*: LONG
##### *Return value*
*LONG* The number of values deleted from the sorted set
##### *Example*
<pre>
$redis->zAdd('key', 1, 'one');
$redis->zAdd('key', 2, 'two');
$redis->zAdd('key', 3, 'three');
$redis->zRemRangeByRank('key', 0, 1); /* 2 */
$redis->zRange('key', 0, -1, array('withscores' => TRUE)); /* array('three' => 3) */
</pre>

## zSize, zCard
##### *Description*
Returns the cardinality of an ordered set.
##### *Parameters*
*key*

##### *Return value*
*Long*, the set's cardinality
##### *Example*
<pre>
$redis->zAdd('key', 0, 'val0');
$redis->zAdd('key', 2, 'val2');
$redis->zAdd('key', 10, 'val10');
$redis->zSize('key'); /* 3 */
</pre>

## zScore
##### *Description*
Returns the score of a given member in the specified sorted set.
##### *Parameters*
*key*
*member*

##### *Return value*
*Double*
##### *Example*
<pre>
$redis->zAdd('key', 2.5, 'val2');
$redis->zScore('key', 'val2'); /* 2.5 */
</pre>

## zRank, zRevRank
##### *Description*
Returns the rank of a given member in the specified sorted set, starting at 0 for the item with the smallest score. zRevRank starts at 0 for the item with the *largest* score.
##### *Parameters*
*key*
*member*
##### *Return value*
*Long*, the item's score.
##### *Example*
<pre>
$redis->delete('z');
$redis->zAdd('key', 1, 'one');
$redis->zAdd('key', 2, 'two');
$redis->zRank('key', 'one'); /* 0 */
$redis->zRank('key', 'two'); /* 1 */
$redis->zRevRank('key', 'one'); /* 1 */
$redis->zRevRank('key', 'two'); /* 0 */
</pre>

## zIncrBy
##### Description
Increments the score of a member from a sorted set by a given amount.
##### Parameters
*key*
*value*: (double) value that will be added to the member's score
*member*
##### Return value
*DOUBLE* the new value
##### Examples
<pre>
$redis->delete('key');
$redis->zIncrBy('key', 2.5, 'member1'); /* key or member1 didn't exist, so member1's score is to 0 before the increment */
          /* and now has the value 2.5  */
$redis->zIncrBy('key', 1, 'member1'); /* 3.5 */
</pre>

## zUnion
##### *Description*
Creates an union of sorted sets given in second argument. The result of the union will be stored in the sorted set defined by the first argument.
The third optionnel argument defines `weights` to apply to the sorted sets in input. In this case, the `weights` will be multiplied by the score of each element in the sorted set before applying the aggregation.
The forth argument defines the `AGGREGATE` option which specify how the results of the union are aggregated.
##### *Parameters*
*keyOutput*
*arrayZSetKeys*
*arrayWeights*
*aggregateFunction* Either "SUM", "MIN", or "MAX": defines the behaviour to use on duplicate entries during the zUnion.

##### *Return value*
*LONG* The number of values in the new sorted set.
##### *Example*
<pre>
$redis->delete('k1');
$redis->delete('k2');
$redis->delete('k3');
$redis->delete('ko1');
$redis->delete('ko2');
$redis->delete('ko3');

$redis->zAdd('k1', 0, 'val0');
$redis->zAdd('k1', 1, 'val1');

$redis->zAdd('k2', 2, 'val2');
$redis->zAdd('k2', 3, 'val3');

$redis->zUnion('ko1', array('k1', 'k2')); /* 4, 'ko1' => array('val0', 'val1', 'val2', 'val3') */

/* Weighted zUnion */
$redis->zUnion('ko2', array('k1', 'k2'), array(1, 1)); /* 4, 'ko2' => array('val0', 'val1', 'val2', 'val3') */
$redis->zUnion('ko3', array('k1', 'k2'), array(5, 1)); /* 4, 'ko3' => array('val0', 'val2', 'val3', 'val1') */
</pre>

## zInter
##### *Description*
Creates an intersection of sorted sets given in second argument. The result of the union will be stored in the sorted set defined by the first argument.
The third optionnel argument defines `weights` to apply to the sorted sets in input. In this case, the `weights` will be multiplied by the score of each element in the sorted set before applying the aggregation.
The forth argument defines the `AGGREGATE` option which specify how the results of the union are aggregated.
##### *Parameters*
*keyOutput*
*arrayZSetKeys*
*arrayWeights*
*aggregateFunction* Either "SUM", "MIN", or "MAX": defines the behaviour to use on duplicate entries during the zInter.

##### *Return value*
*LONG* The number of values in the new sorted set.
##### *Example*
<pre>
$redis->delete('k1');
$redis->delete('k2');
$redis->delete('k3');

$redis->delete('ko1');
$redis->delete('ko2');
$redis->delete('ko3');
$redis->delete('ko4');

$redis->zAdd('k1', 0, 'val0');
$redis->zAdd('k1', 1, 'val1');
$redis->zAdd('k1', 3, 'val3');

$redis->zAdd('k2', 2, 'val1');
$redis->zAdd('k2', 3, 'val3');

$redis->zInter('ko1', array('k1', 'k2')); 				/* 2, 'ko1' => array('val1', 'val3') */
$redis->zInter('ko2', array('k1', 'k2'), array(1, 1)); 	/* 2, 'ko2' => array('val1', 'val3') */

/* Weighted zInter */
$redis->zInter('ko3', array('k1', 'k2'), array(1, 5), 'min'); /* 2, 'ko3' => array('val1', 'val3') */
$redis->zInter('ko4', array('k1', 'k2'), array(1, 5), 'max'); /* 2, 'ko4' => array('val3', 'val1') */

</pre>

## hSet
##### *Description*
Adds a value to the hash stored at key. If this value is already in the hash, `FALSE` is returned.
##### *Parameters*
*key*
*hashKey*
*value*

##### *Return value*
*LONG* `1` if value didn't exist and was added successfully, `0` if the value was already present and was replaced, `FALSE` if there was an error.
##### *Example*
<pre>
$redis->delete('h')
$redis->hSet('h', 'key1', 'hello'); /* 1, 'key1' => 'hello' in the hash at "h" */
$redis->hGet('h', 'key1'); /* returns "hello" */

$redis->hSet('h', 'key1', 'plop'); /* 0, value was replaced. */
$redis->hGet('h', 'key1'); /* returns "plop" */
</pre>

## hSetNx
##### *Description*
Adds a value to the hash stored at key only if this field isn't already in the hash.

##### *Return value*
*BOOL* `TRUE` if the field was set, `FALSE` if it was already present.

##### *Example*
<pre>
$redis->delete('h')
$redis->hSetNx('h', 'key1', 'hello'); /* TRUE, 'key1' => 'hello' in the hash at "h" */
$redis->hSetNx('h', 'key1', 'world'); /* FALSE, 'key1' => 'hello' in the hash at "h". No change since the field wasn't replaced. */
</pre>


## hGet
##### *Description*
Gets a value from the hash stored at key. If the hash table doesn't exist, or the key doesn't exist, `FALSE` is returned.
##### *Parameters*
*key*
*hashKey*

##### *Return value*
*STRING* The value, if the command executed successfully
*BOOL* `FALSE` in case of failure


## hLen
##### *Description*
Returns the length of a hash, in number of items
##### *Parameters*
*key*

##### *Return value*
*LONG* the number of items in a hash, `FALSE` if the key doesn't exist or isn't a hash.
##### *Example*
<pre>
$redis->delete('h')
$redis->hSet('h', 'key1', 'hello');
$redis->hSet('h', 'key2', 'plop');
$redis->hLen('h'); /* returns 2 */
</pre>

## hDel
##### *Description*
Removes a value from the hash stored at key. If the hash table doesn't exist, or the key doesn't exist, `FALSE` is returned.
##### *Parameters*
*key*
*hashKey*

##### *Return value*
*BOOL* `TRUE` in case of success, `FALSE` in case of failure


## hKeys
##### *Description*
Returns the keys in a hash, as an array of strings.

##### *Parameters*
*Key*: key

##### *Return value*
An array of elements, the keys of the hash. This works like PHP's array_keys().

##### *Example*
<pre>
$redis->delete('h');
$redis->hSet('h', 'a', 'x');
$redis->hSet('h', 'b', 'y');
$redis->hSet('h', 'c', 'z');
$redis->hSet('h', 'd', 't');
var_dump($redis->hKeys('h'));
</pre>

Output:
<pre>
array(4) {
[0]=>
string(1) "a"
[1]=>
string(1) "b"
[2]=>
string(1) "c"
[3]=>
string(1) "d"
}
</pre>
The order is random and corresponds to redis' own internal representation of the set structure.

## hVals
##### *Description*
Returns the values in a hash, as an array of strings.

##### *Parameters*
*Key*: key

##### *Return value*
An array of elements, the values of the hash. This works like PHP's array_values().

##### *Example*
<pre>
$redis->delete('h');
$redis->hSet('h', 'a', 'x');
$redis->hSet('h', 'b', 'y');
$redis->hSet('h', 'c', 'z');
$redis->hSet('h', 'd', 't');
var_dump($redis->hVals('h'));
</pre>

Output:
<pre>
array(4) {
[0]=>
string(1) "x"
[1]=>
string(1) "y"
[2]=>
string(1) "z"
[3]=>
string(1) "t"
}
</pre>
The order is random and corresponds to redis' own internal representation of the set structure.

## hGetAll
##### *Description*
Returns the whole hash, as an array of strings indexed by strings.

##### *Parameters*
*Key*: key

##### *Return value*
An array of elements, the contents of the hash.

##### *Example*
<pre>
$redis->delete('h');
$redis->hSet('h', 'a', 'x');
$redis->hSet('h', 'b', 'y');
$redis->hSet('h', 'c', 'z');
$redis->hSet('h', 'd', 't');
var_dump($redis->hGetAll('h'));
</pre>

Output:
<pre>
array(4) {
["a"]=>
string(1) "x"
["b"]=>
string(1) "y"
["c"]=>
string(1) "z"
["d"]=>
string(1) "t"
}
</pre>
The order is random and corresponds to redis' own internal representation of the set structure.

## hExists
##### Description
Verify if the specified member exists in a key.
##### Parameters
*key*
*memberKey*
##### Return value
*BOOL*: If the member exists in the hash table, return `TRUE`, otherwise return `FALSE`.
##### Examples
<pre>
$redis->hSet('h', 'a', 'x');
$redis->hExists('h', 'a'); /*  TRUE */
$redis->hExists('h', 'NonExistingKey'); /* FALSE */
</pre>

## hIncrBy
##### Description
Increments the value of a member from a hash by a given amount.
##### Parameters
*key*
*member*
*value*: (integer) value that will be added to the member's value
##### Return value
*LONG* the new value
##### Examples
<pre>
$redis->delete('h');
$redis->hIncrBy('h', 'x', 2); /* returns 2: h[x] = 2 now. */
$redis->hIncrBy('h', 'x', 1); /* h[x] ← 2 + 1. Returns 3 */
</pre>

## hIncrByFloat
##### Description
Increments the value of a hash member by the provided float value
##### Parameters
*key*
*member*
*value*: (float) value that will be added to the member's value
##### Return value
*FLOAT* the new value
##### Examples
<pre>
$redis->delete('h');
$redis->hIncrByFloat('h','x', 1.5); /* returns 1.5: h[x] = 1.5 now */
$redis->hIncrByFLoat('h', 'x', 1.5); /* returns 3.0: h[x] = 3.0 now */
$redis->hIncrByFloat('h', 'x', -3.0); /* returns 0.0: h[x] = 0.0 now */
</pre>

## hMset
##### Description
Fills in a whole hash. Non-string values are converted to string, using the standard `(string)` cast. NULL values are stored as empty strings.
##### Parameters
*key*
*members*: key → value array
##### Return value
*BOOL*
##### Examples
<pre>
$redis->delete('user:1');
$redis->hMset('user:1', array('name' => 'Joe', 'salary' => 2000));
$redis->hIncrBy('user:1', 'salary', 100); // Joe earns 100 more now.
</pre>

## hMGet
##### Description
Retrieve the values associated to the specified fields in the hash.
##### Parameters
*key*
*memberKeys* Array
##### Return value
*Array* An array of elements, the values of the specified fields in the hash, with the hash keys as array keys.
##### Examples
<pre>
$redis->delete('h');
$redis->hSet('h', 'field1', 'value1');
$redis->hSet('h', 'field2', 'value2');
$redis->hmGet('h', array('field1', 'field2')); /* returns array('field1' => 'value1', 'field2' => 'value2') */
</pre>

## config
##### Description
Get or Set the redis config keys.
##### Parameters
*operation* (string) either `GET` or `SET`
*key* string for `SET`, glob-pattern for `GET`. See http://redis.io/commands/config-get for examples.
*value* optional string (only for `SET`)
##### Return value
*Associative array* for `GET`, key -> value
*bool* for `SET`
##### Examples
<pre>
$redis->config("GET", "*max-*-entries*");
$redis->config("SET", "dir", "/var/run/redis/dumps/");
</pre>

## eval
##### Description
Evaluate a LUA script serverside
##### Parameters
*script* string.
*args* array, optional.
*num_keys* int, optional.
##### Return value
Mixed.  What is returned depends on what the LUA script itself returns, which could be a scalar value (int/string), or an array.
Arrays that are returned can also contain other arrays, if that's how it was set up in your LUA script.  If there is an error
executing the LUA script, the getLastError() function can tell you the message that came back from Redis (e.g. compile error).
##### Examples
<pre>
$redis->eval("return 1"); // Returns an integer: 1
$redis->eval("return {1,2,3}"); // Returns Array(1,2,3)
$redis->del('mylist');
$redis->rpush('mylist','a');
$redis->rpush('mylist','b');
$redis->rpush('mylist','c');
// Nested response:  Array(1,2,3,Array('a','b','c'));
$redis->eval("return {1,2,3,redis.call('lrange','mylist',0,-1)}}");
</pre>

## evalSha
##### Description
Evaluate a LUA script serverside, from the SHA1 hash of the script instead of the script itself.  In order to run this command Redis
will have to have already loaded the script, either by running it or via the SCRIPT LOAD command.
##### Parameters
*script_sha* string.  The sha1 encoded hash of the script you want to run.
*args* array, optional.  Arguments to pass to the LUA script.
*num_keys* int, optional.  The number of arguments that should go into the KEYS array, vs. the ARGV array when Redis spins the script
##### Return value
Mixed.  See EVAL
##### Examples
<pre>
$script = 'return 1';
$sha = $redis->script('load', $script);
$redis->evalSha($sha); // Returns 1
</pre>

## script
##### Description
Execute the Redis SCRIPT command to perform various operations on the scripting subsystem.
##### Usage
<pre>
$redis->script('load', $script);
$redis->script('flush');
$redis->script('kill');
$redis->script('exists', $script1, [$script2, $script3, ...]);
</pre>
##### Return value
* SCRIPT LOAD will return the SHA1 hash of the passed script on success, and FALSE on failure.
* SCRIPT FLUSH should always return TRUE
* SCRIPT KILL will return true if a script was able to be killed and false if not
* SCRIPT EXISTS will return an array with TRUE or FALSE for each passed script

## getLastError
##### Description
The last error message (if any)
##### Parameters
*none*
##### Return Value
A string with the last returned script based error message, or NULL if there is no error
##### Examples
<pre>
$redis->eval('this-is-not-lua');
$err = $redis->getLastError();
// "ERR Error compiling script (new function): user_script:1: '=' expected near '-'"
</pre>

## clearLastError
##### Description
Clear the last error message
##### Parameters
*none*
##### Return Value
*BOOL* TRUE
##### Examples
<pre>
$redis->set('x', 'a');
$redis->incr('x');
$err = $redis->getLastError();
// "ERR value is not an integer or out of range"
$redis->clearLastError();
$err = $redis->getLastError();
// NULL
</pre>

## _prefix
##### Description
A utility method to prefix the value with the prefix setting for phpredis.
##### Parameters
*value* string.  The value you wish to prefix
##### Return value
If a prefix is set up, the value now prefixed.  If there is no prefix, the value will be returned unchanged.
##### Examples
<pre>
$redis->setOpt(Redis::OPT_PREFIX, 'my-prefix:');
$redis->_prefix('my-value'); // Will return 'my-prefix:my-value'
</pre>

## _unserialize
##### Description
A utility method to unserialize data with whatever serializer is set up.  If there is no serializer set, the value will be
returned unchanged.  If there is a serializer set up, and the data passed in is malformed, an exception will be thrown.
This can be useful if phpredis is serializing values, and you return something from redis in a LUA script that is serialized.
##### Parameters
*value* string.  The value to be unserialized
##### Examples
<pre>
$redis->setOpt(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);
$redis->_unserialize('a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}'); // Will return Array(1,2,3)
</pre>

## dump
##### Description
Dump a key out of a redis database, the value of which can later be passed into redis using the RESTORE command.  The data
that comes out of DUMP is a binary representation of the key as Redis stores it.
##### Parameters
*key* string
##### Return value
The Redis encoded value of the key, or FALSE if the key doesn't exist
##### Examples
<pre>
$redis->set('foo', 'bar');
$val = $redis->dump('foo'); // $val will be the Redis encoded key value
</pre>

## restore
##### Description
Restore a key from the result of a DUMP operation.
##### Parameters
*key* string.  The key name
*ttl* integer.  How long the key should live (if zero, no expire will be set on the key)
*value* string (binary).  The Redis encoded key value (from DUMP)
##### Examples
<pre>
$redis->set('foo', 'bar');
$val = $redis->dump('foo');
$redis->restore('bar', 0, $val); // The key 'bar', will now be equal to the key 'foo'
</pre>

## migrate
##### Description
Migrates a key to a different Redis instance.
##### Parameters
*host* string.  The destination host
*port* integer.  The TCP port to connect to.
*key* string. The key to migrate.
*destination-db* integer.  The target DB.
*timeout* integer.  The maximum amount of time given to this transfer.
##### Examples
<pre>
$redis->migrate('backup', 6379, 'foo', 0, 3600);
</pre>

## time
##### Description
Return the current Redis server time.
##### Parameters
(none)
##### Return value
If successfull, the time will come back as an associative array with element zero being
the unix timestamp, and element one being microseconds.
##### Examples
<pre>
$redis->time();
</pre>
