document.addEventListener("DOMContentLoaded", () => {
  document.addEventListener("click", (e) => {
    const likeButton = e.target.closest(".like-btn")
    if (likeButton) {
      e.preventDefault()
      const form = likeButton.closest("form")
      const url = form.action
      const method = form.querySelector('input[name="_method"]')
        ? form.querySelector('input[name="_method"]').value
        : "POST"
      const postId = likeButton.dataset.postId
      const csrfToken = document.querySelector('meta[name="csrf-token"]').content

      fetch(url, {
        method: method === "DELETE" ? "DELETE" : "POST",
        headers: {
          "X-CSRF-TOKEN": csrfToken,
          "Content-Type": "application/json",
          Accept: "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
        credentials: "same-origin",
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            const likeCount = likeButton.querySelector(".like-count")
            likeCount.textContent = data.count

            if (data.liked) {
              likeButton.classList.add("text-red-500")
              likeButton.classList.remove("text-gray-400")
              likeButton.querySelector("i").classList.remove("ri-heart-line")
              likeButton.querySelector("i").classList.add("ri-heart-fill")

              form.action = `/posts/${postId}/likes`

              if (!form.querySelector('input[name="_method"]')) {
                const methodInput = document.createElement("input")
                methodInput.type = "hidden"
                methodInput.name = "_method"
                methodInput.value = "DELETE"
                form.appendChild(methodInput)
              }
            } else {
              likeButton.classList.remove("text-red-500")
              likeButton.classList.add("text-gray-400")
              likeButton.querySelector("i").classList.add("ri-heart-line")
              likeButton.querySelector("i").classList.remove("ri-heart-fill")

              form.action = `/posts/${postId}/likes`

              const methodInput = form.querySelector('input[name="_method"]')
              if (methodInput) {
                methodInput.remove()
              }
            }
          }
        })
        .catch((error) => console.error("Erreur:", error))
    }
  })

  document.addEventListener("click", (e) => {
    const bookmarkButton = e.target.closest(".bookmark-btn")
    if (bookmarkButton) {
      e.preventDefault()
      const form = bookmarkButton.closest("form")
      const url = form.action
      const method = form.querySelector('input[name="_method"]')
        ? form.querySelector('input[name="_method"]').value
        : "POST"
      const postId = bookmarkButton.dataset.postId
      const csrfToken = document.querySelector('meta[name="csrf-token"]').content

      fetch(url, {
        method: method === "DELETE" ? "DELETE" : "POST",
        headers: {
          "X-CSRF-TOKEN": csrfToken,
          "Content-Type": "application/json",
          Accept: "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
        credentials: "same-origin",
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            if (data.bookmarked) {
              bookmarkButton.classList.add("text-blue-500")
              bookmarkButton.classList.remove("text-gray-400")
              bookmarkButton.querySelector("i").classList.remove("ri-bookmark-line")
              bookmarkButton.querySelector("i").classList.add("ri-bookmark-fill")

              form.action = `/posts/${postId}/bookmarks`

              if (!form.querySelector('input[name="_method"]')) {
                const methodInput = document.createElement("input")
                methodInput.type = "hidden"
                methodInput.name = "_method"
                methodInput.value = "DELETE"
                form.appendChild(methodInput)
              }
            } else {
              bookmarkButton.classList.remove("text-blue-500")
              bookmarkButton.classList.add("text-gray-400")
              bookmarkButton.querySelector("i").classList.add("ri-bookmark-line")
              bookmarkButton.querySelector("i").classList.remove("ri-bookmark-fill")

              form.action = `/posts/${postId}/bookmarks`

              const methodInput = form.querySelector('input[name="_method"]')
              if (methodInput) {
                methodInput.remove()
              }
            }
          }
        })
        .catch((error) => console.error("Erreur:", error))
    }
  })

  document.addEventListener("click", (e) => {
    const followButton = e.target.closest(".follow-btn")
    if (followButton) {
      e.preventDefault()
      const form = followButton.closest("form")
      const url = form.action
      const method = form.querySelector('input[name="_method"]')
        ? form.querySelector('input[name="_method"]').value
        : "POST"
      const userId = followButton.dataset.userId
      const csrfToken = document.querySelector('meta[name="csrf-token"]').content

      fetch(url, {
        method: method === "DELETE" ? "DELETE" : "POST",
        headers: {
          "X-CSRF-TOKEN": csrfToken,
          "Content-Type": "application/json",
          Accept: "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
        credentials: "same-origin",
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            if (data.following) {
              followButton.textContent = "Unfollow"
              followButton.classList.remove("bg-blue-500", "hover:bg-blue-600")
              followButton.classList.add("bg-gray-600", "hover:bg-gray-700")

              form.action = `/users/${userId}/follow`

              if (!form.querySelector('input[name="_method"]')) {
                const methodInput = document.createElement("input")
                methodInput.type = "hidden"
                methodInput.name = "_method"
                methodInput.value = "DELETE"
                form.appendChild(methodInput)
              }

              const followerCount = document.querySelector(".follower-count")
              if (followerCount) {
                const count = Number.parseInt(followerCount.textContent) + 1
                followerCount.textContent = `${count}+ abonnés`
              }
            } else {
              followButton.textContent = "Follow"
              followButton.classList.add("bg-blue-500", "hover:bg-blue-600")
              followButton.classList.remove("bg-gray-600", "hover:bg-gray-700")

              form.action = `/users/${userId}/follow`

              const methodInput = form.querySelector('input[name="_method"]')
              if (methodInput) {
                methodInput.remove()
              }

              const followerCount = document.querySelector(".follower-count")
              if (followerCount) {
                const countText = followerCount.textContent
                const count = Number.parseInt(countText) - 1
                followerCount.textContent = `${count}+ abonnés`
              }
            }
          }
        })
        .catch((error) => console.error("Erreur:", error))
    }
  })

  document.addEventListener("click", (e) => {
    const shareButton = e.target.closest(".share-btn")
    if (shareButton) {
      e.preventDefault()
      const form = shareButton.closest("form")
      const url = form.action
      const postId = shareButton.dataset.postId
      const csrfToken = document.querySelector('meta[name="csrf-token"]').content

      shareButton.disabled = true
      shareButton.classList.add("opacity-50")

      fetch(url, {
        method: "POST",
        headers: {
          "X-CSRF-TOKEN": csrfToken,
          "Content-Type": "application/json",
          Accept: "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
        credentials: "same-origin",
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            const shareCount = shareButton.querySelector(".share-count")
            if (shareCount) {
              shareCount.textContent = data.count
            }

            shareButton.classList.add("active", "share-active")

            const isHomePage = document.getElementById("posts-container") !== null
            const isProfilePage = document.querySelector(".publications-container") !== null

            if (isHomePage && data.postHtml) {
              const postsContainer = document.getElementById("posts-container")
              const tempContainer = document.createElement("div")
              tempContainer.innerHTML = data.postHtml

              const newPostElement = tempContainer.firstElementChild

              newPostElement.classList.add("new-shared-post")

              postsContainer.insertBefore(newPostElement, postsContainer.firstChild)

              newPostElement.scrollIntoView({ behavior: "smooth", block: "start" })
            }

            if (isProfilePage && data.profilePostHtml && data.isCurrentUserProfile) {
              const publicationsContainer = document.querySelector(".publications-container")

              const emptyPublications = publicationsContainer.querySelector(".empty-publications")
              if (emptyPublications) {
                emptyPublications.remove()
              }

              const tempContainer = document.createElement("div")
              tempContainer.innerHTML = data.profilePostHtml

              const newPostElement = tempContainer.firstElementChild

              newPostElement.classList.add("new-shared-post")

              publicationsContainer.insertBefore(newPostElement, publicationsContainer.firstChild)

              newPostElement.scrollIntoView({ behavior: "smooth", block: "start" })

              initPostEvents(newPostElement)
            }

            const successMessage = document.createElement("div")
            successMessage.className = "fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50"
            successMessage.textContent = "Post partagé avec succès!"
            document.body.appendChild(successMessage)

            setTimeout(() => {
              successMessage.remove()
            }, 3000)
          }
        })
        .catch((error) => {
          console.error("Erreur:", error)
          const errorMessage = document.createElement("div")
          errorMessage.className = "fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg z-50"
          errorMessage.textContent = "Erreur lors du partage du post"
          document.body.appendChild(errorMessage)

          setTimeout(() => {
            errorMessage.remove()
          }, 3000)
        })
        .finally(() => {
          shareButton.disabled = false
          shareButton.classList.remove("opacity-50")
        })
    }
  })

  const commentForms = document.querySelectorAll(".comment-form")
  commentForms.forEach((form) => {
    form.addEventListener("submit", (e) => {
      e.preventDefault()
      const url = form.action
      const formData = new FormData(form)
      const csrfToken = document.querySelector('meta[name="csrf-token"]').content

      fetch(url, {
        method: "POST",
        headers: {
          "X-CSRF-TOKEN": csrfToken,
          Accept: "application/json",
          "X-Requested-With": "XMLHttpRequest",
        },
        body: formData,
        credentials: "same-origin",
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            form.querySelector("textarea").value = ""
            const commentCountElements = document.querySelectorAll(`.comment-count-${data.post_id}`)
            commentCountElements.forEach((el) => {
              el.textContent = data.count
            })
            const postElement = form.closest(".card")
            const commentsContainer = postElement.querySelector(".post-comments")
            if (commentsContainer && data.html) {
              const tempDiv = document.createElement("div")
              tempDiv.innerHTML = data.html.trim()
              const commentElement = tempDiv.firstChild
              commentsContainer.insertBefore(commentElement, form)
              initCommentMenu(commentElement)
            }
          }
        })
        .catch((error) => console.error("Erreur:", error))
    })
  })
  function initCommentMenu(commentElement) {
    const menuToggle = commentElement.querySelector(".comment-menu-toggle")
    if (menuToggle) {
      menuToggle.addEventListener("click", function (e) {
        e.preventDefault()
        e.stopPropagation()

        const dropdown = this.closest(".comment-menu").querySelector(".comment-menu-dropdown")

        document.querySelectorAll(".comment-menu-dropdown").forEach((menu) => {
          if (menu !== dropdown) menu.classList.add("hidden")
        })

        dropdown.classList.toggle("hidden")
      })

      const editBtn = commentElement.querySelector(".edit-comment-btn")
      if (editBtn) {
        editBtn.addEventListener("click", function () {
          const commentId = this.getAttribute("data-comment-id")
          const commentContent = this.getAttribute("data-comment-content")

          this.closest(".comment-menu-dropdown").classList.add("hidden")

          const editCommentForm = document.getElementById("edit-comment-form")
          const editCommentContent = document.getElementById("edit-comment-content")
          const editCommentModal = document.getElementById("edit-comment-modal")

          editCommentForm.action = `/comments/${commentId}`
          editCommentContent.value = commentContent

          editCommentModal.classList.remove("hidden")
        })
      }
    }
  }

  function initPostEvents(postElement) {
    const menuButton = postElement.querySelector(".publication-menu-btn")
    if (menuButton) {
      menuButton.addEventListener("click", function (e) {
        e.stopPropagation()
        const dropdown = this.parentNode.querySelector(".publication-menu-dropdown")
        dropdown.classList.toggle("hidden")
      })
    }

    const prevButton = postElement.querySelector(".prev-image")
    const nextButton = postElement.querySelector(".next-image")

    if (prevButton) {
      prevButton.addEventListener("click", function () {
        const container = this.closest(".publication-images-container")
        navigateImages(container, "prev")
      })
    }

    if (nextButton) {
      nextButton.addEventListener("click", function () {
        const container = this.closest(".publication-images-container")
        navigateImages(container, "next")
      })
    }

    const paginationDots = postElement.querySelectorAll(".pagination-dot")
    paginationDots.forEach((dot) => {
      dot.addEventListener("click", function () {
        const container = this.closest(".publication-images-container")
        const imagesScroll = container.querySelector(".publication-images-scroll")
        const index = Number.parseInt(this.getAttribute("data-index"))

        imagesScroll.setAttribute("data-current-image", index)
        imagesScroll.style.transform = `translateX(-${index * 100}%)`
        const indicators = container.querySelectorAll(".pagination-dot")
        indicators.forEach((d, i) => {
          if (i === index) {
            d.classList.add("active")
          } else {
            d.classList.remove("active")
          }
        })
      })
    })
  }

  function navigateImages(container, direction) {
    const imagesScroll = container.querySelector(".publication-images-scroll")
    const totalImages = Number.parseInt(imagesScroll.getAttribute("data-total-images"))
    let currentIndex = Number.parseInt(imagesScroll.getAttribute("data-current-image"))

    if (direction === "next") {
      currentIndex = (currentIndex + 1) % totalImages
    } else {
      currentIndex = (currentIndex - 1 + totalImages) % totalImages
    }

    imagesScroll.setAttribute("data-current-image", currentIndex)
    imagesScroll.style.transform = `translateX(-${currentIndex * 100}%)`
    const indicators = container.querySelectorAll(".pagination-dot")
    indicators.forEach((dot, idx) => {
      if (idx === currentIndex) {
        dot.classList.add("active")
      } else {
        dot.classList.remove("active")
      }
    })
  }
})