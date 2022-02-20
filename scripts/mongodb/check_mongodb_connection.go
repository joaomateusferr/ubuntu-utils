//sudo apt  install golang-go
//go env GOPATH
//on ~/go/src/mongo/main.go ->  go get go.mongodb.org/mongo-driver/mongo

package main

import (
	"context"
	"log"
	"fmt"

	"go.mongodb.org/mongo-driver/mongo"
	"go.mongodb.org/mongo-driver/bson"
	"go.mongodb.org/mongo-driver/mongo/options"
)

// Connection URI
const uri = "mongodb://localhost:27017/?maxPoolSize=20&w=majority"

func main() {
	// Create a new client and connect to the server
	client, err := mongo.Connect(context.TODO(), options.Client().ApplyURI(uri))

	if err != nil {
		panic(err)
	}

	defer func() {
		if err = client.Disconnect(context.TODO()); err != nil {
			panic(err)
		}
	}()

	coll := client.Database("test").Collection("testCollection")

	filter := bson.D{{"_id", "1"}}

	cursor, err := coll.Find(context.TODO(), filter)

	if err != nil {
		log.Fatal(err)
	}

	var profile []bson.M
	if err = cursor.All(context.TODO(), &profile); err != nil {
		log.Fatal(err)
	}

	fmt.Println(profile)
}

