package main

import (
	"github.com/gin-gonic/gin"
	"io/ioutil"
	"net/http"
	"path/filepath"
)

func main() {
	r := gin.Default()

	r.GET("/", func(c *gin.Context) {
		c.HTML(http.StatusOK, "index.html", gin.H{})
	})

	r.GET("/read", func(c *gin.Context) {
		file := c.Query("file")
		content, err := ioutil.ReadFile(filepath.Clean(file))
		if err != nil {
			c.String(http.StatusInternalServerError, "Error reading file: %v", err)
			return
		}

		c.String(http.StatusOK, string(content))
	})

	r.LoadHTMLFiles("templates/index.html")
	r.Run(":8080")
}

