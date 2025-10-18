helm repo add argo https://argoproj.github.io/argo-helm


helm upgrade --install argocd argo/argo-cd -n argocd --create-namespace -f values.yaml



sudo apt-get update && sudo apt-get install apache2-utils

htpasswd -n -B admin

htpasswd -nbBC 10 "" admin | tr -d ':\n'


helm upgrade --install argocd-apps argo/argocd-apps -n argocd -f app.yaml

 helm upgrade --install my-kube-prometheus-stack prometheus-community/kube-prometheus-stack -n monitoring  --create-namespace -f prom-values.yaml
