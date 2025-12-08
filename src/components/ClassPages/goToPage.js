export function usePageNavigation(emit) {
  const goToPage = (pageNumber) => {
    emit('flip', pageNumber)
  }
  return {
    goToPage
  }
}