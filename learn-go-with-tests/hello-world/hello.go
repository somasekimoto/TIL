package main

import "fmt"

const spanish = "Spanish"
const english = "English"
const french = "French"
const englishHelloPrefix = "Hello, "
const spanishHelloPrefix = "Hola, "
const frenchHelloPrefix = "Bonjour, "

func Hello(name string, language ...string) string {
	lang := english
	if len(language) > 0 {
		lang = language[0]
	}

	if name == "" {
		name = "World"
	}

	prefix := englishHelloPrefix

	switch lang {
	case french:
		prefix = frenchHelloPrefix
	case spanish:
		prefix = spanishHelloPrefix
	}

	return prefix + name
}

func main() {
	fmt.Println(Hello("world"))
}
